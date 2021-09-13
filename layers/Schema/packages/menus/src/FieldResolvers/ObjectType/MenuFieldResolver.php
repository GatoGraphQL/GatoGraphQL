<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPSchema\Menus\ObjectModels\MenuItem;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuItemTypeResolver;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuTypeResolver;

class MenuFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'items',
            'itemDataEntries',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'itemDataEntries' => SchemaDefinition::TYPE_OBJECT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'items',
            'itemDataEntries'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'itemDataEntries':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'flat',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Flatten the items', 'menus'),
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'items' => $this->translationAPI->__('The menu items', 'menus'),
            'itemDataEntries' => $this->translationAPI->__('The data for the menu items', 'menus'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $menuTypeAPI = MenuTypeAPIFacade::getInstance();
        $menu = $resultItem;
        switch ($fieldName) {
            case 'itemDataEntries':
                $isFlat = $fieldArgs['flat'] ?? false;
                $menuItems = $menuTypeAPI->getMenuItems($menu);
                $entries = array();
                if ($menuItems) {
                    foreach ($menuItems as $menuItem) {
                        // Convert object to array
                        // @see https://stackoverflow.com/a/18576902
                        $item_value = json_decode(json_encode($menuItem), true);
                        // Prepare array where to append the children items
                        if (!$isFlat) {
                            $item_value['children'] = [];
                        }
                        $entries[] = $item_value;
                    }
                }
                if ($isFlat) {
                    return $entries;
                }
                /**
                 * Reproduce the menu layout in the array
                 */
                $arrangedEntries = [];
                foreach ($entries as $menuItemData) {
                    $arrangedEntriesPointer = &$arrangedEntries;
                    // Reproduce the list of parents
                    if ($menuItemParentID = $menuItemData['parentID']) {
                        $menuItemAncestorIDs = [];
                        while ($menuItemParentID !== null) {
                            $menuItemAncestorIDs[] = $menuItemParentID;
                            $menuItemParentPos = $this->findEntryPosition($menuItemParentID, $entries);
                            $menuItemParentID = $entries[$menuItemParentPos]['parentID'];
                        }
                        // Navigate to that position, and attach the menuItem
                        foreach (array_reverse($menuItemAncestorIDs) as $menuItemAncestorID) {
                            $menuItemAncestorPos = $this->findEntryPosition($menuItemAncestorID, $arrangedEntriesPointer);
                            $arrangedEntriesPointer = &$arrangedEntriesPointer[$menuItemAncestorPos]['children'];
                        }
                    }
                    $arrangedEntriesPointer[] = $menuItemData;
                }
                return $arrangedEntries;
            case 'items':
                $menuItems = $menuTypeAPI->getMenuItems($menu);

                // Save the MenuItems on the dynamic registry
                foreach ($menuItems as $menuItem) {
                    $this->menuItemRuntimeRegistry->storeMenuItem($menuItem);
                }

                // Return the IDs for the top-level items (those with no parent)
                return array_map(
                    fn (MenuItem $menuItem) => $menuItem->id,
                    array_filter(
                        $menuItems,
                        fn (MenuItem $menuItem) => $menuItem->parentID === null
                    )
                );
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    protected function findEntryPosition(string | int $menuItemID, array $entries): int
    {
        $entriesCount = count($entries);
        for ($pos = 0; $pos < $entriesCount; $pos++) {
            /**
             * Watch out! Can't use `===` because (for some reason) the same value
             * could be passed as int or string!
             */
            if ($entries[$pos]['id'] == $menuItemID) {
                return $pos;
            }
        }
        // It will never reach here, so return anything
        return 0;
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'items':
                return MenuItemTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
