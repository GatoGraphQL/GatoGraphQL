<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPSchema\Menus\TypeResolvers\MenuItemTypeResolver;
use PoPSchema\Menus\TypeResolvers\MenuTypeResolver;

class MenuFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(MenuTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'itemDataEntries',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            // 'items' => SchemaDefinition::TYPE_ID,
            'itemDataEntries' => SchemaDefinition::TYPE_MIXED,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'itemDataEntries' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
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
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'itemDataEntries' => $this->translationAPI->__('The menu items', 'menus'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
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
                $itemsData = $menuTypeAPI->getMenuItemsData($menu);
                $entries = array();
                if ($itemsData) {
                    // Load these item data-fields. If other set needed, create another $field
                    $item_data_fields = array('id', 'title', 'alt', 'classes', 'url', 'target', 'parentID', 'objectID', 'additionalAttrs');
                    /**
                     * @var MenuItemTypeResolver
                     */
                    $menuItemTypeResolver = $this->instanceManager->getInstance(MenuItemTypeResolver::class);
                    foreach ($itemsData as $itemData) {
                        $item_value = array();
                        foreach ($item_data_fields as $item_data_field) {
                            $menuItemValue = $menuItemTypeResolver->resolveValue($itemData, $item_data_field, $variables, $expressions, $options);
                            if (GeneralUtils::isError($menuItemValue)) {
                                return $menuItemValue;
                            }
                            $item_value[$item_data_field] = $menuItemValue;
                        }
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
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
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

    // public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    // {
    //     switch ($fieldName) {
    //         case 'items':
    //             return MenuItemTypeResolver::class;
    //     }

    //     return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    // }
}
