<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Menus\TypeResolvers\MenuItemTypeResolver;

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
            'menuItemParent',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'title' => SchemaDefinition::TYPE_STRING,
            'alt' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'classes' => SchemaDefinition::TYPE_STRING,
            'target' => SchemaDefinition::TYPE_STRING,
            'additionalAttrs' => SchemaDefinition::TYPE_STRING,
            'objectID' => SchemaDefinition::TYPE_ID,
            'menuItemParent' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'title' => $translationAPI->__('', ''),
            'alt' => $translationAPI->__('', ''),
            'url' => $translationAPI->__('', ''),
            'classes' => $translationAPI->__('', ''),
            'target' => $translationAPI->__('', ''),
            'additionalAttrs' => $translationAPI->__('', ''),
            'objectID' => $translationAPI->__('', ''),
            'menuItemParent' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $cmsmenusresolver = \PoPSchema\Menus\ObjectPropertyResolverFactory::getInstance();
        $cmsmenusapi = \PoPSchema\Menus\FunctionAPIFactory::getInstance();
        $menu_item = $resultItem;
        switch ($fieldName) {
            case 'title':
                return $cmsmenusapi->getMenuItemTitle($menu_item);

            case 'alt':
                return $cmsmenusresolver->getMenuItemTitle($menu_item);

            case 'url':
                return $cmsmenusresolver->getMenuItemUrl($menu_item);

            case 'classes':
                // Copied from nav-menu-template.php function start_el
                $classes = $cmsmenusresolver->getMenuItemClasses($menu_item);
                $classes = empty($classes) ? array() : (array) $classes;
                $classes[] = 'menu-item';
                $classes[] = 'menu-item-' . $cmsmenusresolver->getMenuItemId($menu_item);
                if ($parent = $cmsmenusresolver->getMenuItemParent($menu_item)) {
                    $classes[] = 'menuItemParent';
                    $classes[] = 'menu-item-parent-' . $parent;
                }
                if ($object_id = $cmsmenusresolver->getMenuItemObjectId($menu_item)) {
                    $classes[] = 'menu-item-object-id-' . $object_id;
                }
                return join(' ', HooksAPIFacade::getInstance()->applyFilters('menuitem:classes', array_filter($classes), $menu_item, array()));

            case 'target':
                return $cmsmenusresolver->getMenuItemTarget($menu_item);

            case 'additionalAttrs':
                // Using the description, because WP does not give a field for extra attributes when creating a menu,
                // and this is needed to add target="addons" for the Add ContentPost link
                return $cmsmenusresolver->getMenuItemDescription($menu_item);

            case 'objectID':
                return $cmsmenusresolver->getMenuItemObjectId($menu_item);

            case 'menuItemParent':
                return $cmsmenusresolver->getMenuItemParent($menu_item);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
