<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoPSchema\Menus\ObjectModels\MenuItem;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class MenuObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry = null;
    private ?JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver = null;
    private ?MenuItemObjectTypeResolver $menuItemObjectTypeResolver = null;
    private ?MenuTypeAPIInterface $menuTypeAPI = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    public function setMenuItemRuntimeRegistry(MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry): void
    {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }
    protected function getMenuItemRuntimeRegistry(): MenuItemRuntimeRegistryInterface
    {
        return $this->menuItemRuntimeRegistry ??= $this->instanceManager->getInstance(MenuItemRuntimeRegistryInterface::class);
    }
    public function setJSONObjectScalarTypeResolver(JSONObjectScalarTypeResolver $jsonObjectScalarTypeResolver): void
    {
        $this->jsonObjectScalarTypeResolver = $jsonObjectScalarTypeResolver;
    }
    protected function getJSONObjectScalarTypeResolver(): JSONObjectScalarTypeResolver
    {
        return $this->jsonObjectScalarTypeResolver ??= $this->instanceManager->getInstance(JSONObjectScalarTypeResolver::class);
    }
    public function setMenuItemObjectTypeResolver(MenuItemObjectTypeResolver $menuItemObjectTypeResolver): void
    {
        $this->menuItemObjectTypeResolver = $menuItemObjectTypeResolver;
    }
    protected function getMenuItemObjectTypeResolver(): MenuItemObjectTypeResolver
    {
        return $this->menuItemObjectTypeResolver ??= $this->instanceManager->getInstance(MenuItemObjectTypeResolver::class);
    }
    public function setMenuTypeAPI(MenuTypeAPIInterface $menuTypeAPI): void
    {
        $this->menuTypeAPI = $menuTypeAPI;
    }
    protected function getMenuTypeAPI(): MenuTypeAPIInterface
    {
        return $this->menuTypeAPI ??= $this->instanceManager->getInstance(MenuTypeAPIInterface::class);
    }
    public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'items',
            'itemDataEntries',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'items' => $this->getMenuItemObjectTypeResolver(),
            'itemDataEntries' => $this->getJsonObjectScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'items',
            'itemDataEntries'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'itemDataEntries' => [
                'flat' => $this->getBooleanScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['itemDataEntries' => 'flat'] => $this->translationAPI->__('Flatten the items', 'menus'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'items' => $this->translationAPI->__('The menu items', 'menus'),
            'itemDataEntries' => $this->translationAPI->__('The data for the menu items', 'menus'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $menu = $object;
        switch ($fieldName) {
            case 'itemDataEntries':
                $isFlat = $fieldArgs['flat'] ?? false;
                $menuItems = $this->getMenuTypeAPI()->getMenuItems($menu);
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
                $menuItems = $this->getMenuTypeAPI()->getMenuItems($menu);

                // Save the MenuItems on the dynamic registry
                foreach ($menuItems as $menuItem) {
                    $this->getMenuItemRuntimeRegistry()->storeMenuItem($menuItem);
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
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
}
