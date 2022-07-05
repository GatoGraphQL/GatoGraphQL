<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
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
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $menuItemTypeAPI = MenuItemTypeAPIFacade::getInstance();
        $menuItem = $object;
        switch ($field->getName()) {
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
