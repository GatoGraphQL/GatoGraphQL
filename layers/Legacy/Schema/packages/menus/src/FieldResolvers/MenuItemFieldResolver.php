<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectTypeResolverInterface;
use PoPSchema\Menus\Facades\MenuItemTypeAPIFacade;

/**
 * Move the classes added to field "classes" to a hook!
 * Kept "classes" clean in this same FieldResolver, in the actual menus package
 */
abstract class MenuItemFieldResolver extends AbstractDBDataFieldResolver
{
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $menuItemTypeAPI = MenuItemTypeAPIFacade::getInstance();
        $menuItem = $resultItem;
        switch ($fieldName) {
            case 'classes':
                // Copied from nav-menu-template.php function start_el
                $classes = $menuItemTypeAPI->getMenuItemClasses($menuItem);
                $classes = empty($classes) ? array() : (array) $classes;
                $classes[] = 'menu-item';
                $classes[] = 'menu-item-' . $menuItemTypeAPI->getMenuItemID($menuItem);
                if ($parentID = $menuItemTypeAPI->getMenuItemParentID($menuItem)) {
                    $classes[] = 'menu-item-parent';
                    $classes[] = 'menu-item-parent-' . $parentID;
                }
                if ($objectID = $menuItemTypeAPI->getMenuItemObjectID($menuItem)) {
                    $classes[] = 'menu-item-object-id-' . $objectID;
                }
                return $this->hooksAPI->applyFilters('menuitem:classes', array_filter($classes), $menuItem, array());
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
