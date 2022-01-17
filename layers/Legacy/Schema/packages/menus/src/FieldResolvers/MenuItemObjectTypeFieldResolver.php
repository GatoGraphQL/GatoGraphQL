<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Menus\Facades\MenuItemTypeAPIFacade;

/**
 * Move the classes added to field "classes" to a hook!
 * Kept "classes" clean in this same ObjectTypeFieldResolver, in the actual menus package
 */
abstract class MenuItemObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
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
        $menuItemTypeAPI = MenuItemTypeAPIFacade::getInstance();
        $menuItem = $object;
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
                return App::applyFilters('menuitem:classes', array_filter($classes), $menuItem, array());
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
