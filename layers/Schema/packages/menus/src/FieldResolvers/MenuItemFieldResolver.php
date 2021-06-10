<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Menus\TypeResolvers\MenuItemTypeResolver;
use PoPSchema\Menus\Facades\MenuItemTypeAPIFacade;

class MenuItemFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(MenuItemTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'title',
            'alt',
            'url',
            'classes',
            'target',
            'additionalAttrs',
            'objectID',
            'parentID',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'title' => SchemaDefinition::TYPE_STRING,
            'alt' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'classes' => SchemaDefinition::TYPE_STRING,
            'target' => SchemaDefinition::TYPE_STRING,
            'additionalAttrs' => SchemaDefinition::TYPE_STRING,
            'objectID' => SchemaDefinition::TYPE_ID,
            'parentID' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'title' => $this->translationAPI->__('Menu item title', 'menus'),
            'alt' => $this->translationAPI->__('Menu item alt', 'menus'),
            'url' => $this->translationAPI->__('Menu item URL', 'menus'),
            'classes' => $this->translationAPI->__('Menu item classes', 'menus'),
            'target' => $this->translationAPI->__('Menu item target', 'menus'),
            'additionalAttrs' => $this->translationAPI->__('Menu item additional attributes', 'menus'),
            'objectID' => $this->translationAPI->__('ID of the object linked to by the menu item ', 'menus'),
            'parentID' => $this->translationAPI->__('Menu item\'s parent ID', 'menus'),
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
        $menuItemTypeAPI = MenuItemTypeAPIFacade::getInstance();
        $menuItem = $resultItem;
        switch ($fieldName) {
            case 'title':
            case 'alt':
                return $menuItemTypeAPI->getMenuItemTitle($menuItem);

            case 'url':
                return $menuItemTypeAPI->getMenuItemURL($menuItem);

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
                return join(' ', $this->hooksAPI->applyFilters('menuitem:classes', array_filter($classes), $menuItem, array()));

            case 'target':
                return $menuItemTypeAPI->getMenuItemTarget($menuItem);

            case 'additionalAttrs':
                // Using the description, because WP does not give a field for extra attributes when creating a menu,
                // and this is needed to add target="addons" for the Add ContentPost link
                return $menuItemTypeAPI->getMenuItemDescription($menuItem);

            case 'objectID':
                return $menuItemTypeAPI->getMenuItemObjectID($menuItem);

            case 'parentID':
                return $menuItemTypeAPI->getMenuItemParentID($menuItem);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
