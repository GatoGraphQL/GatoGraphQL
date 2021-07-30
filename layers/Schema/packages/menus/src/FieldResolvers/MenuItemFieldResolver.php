<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Menus\ObjectModels\MenuItem;
use PoPSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoPSchema\Menus\TypeResolvers\MenuItemTypeResolver;

class MenuItemFieldResolver extends AbstractDBDataFieldResolver
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

    public function getClassesToAttachTo(): array
    {
        return array(MenuItemTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            // This field is special in that it is retrieved from the registry
            'children',
            // All other fields are properties in the object
            'title',
            'url',
            'classes',
            'target',
            'description',
            'objectID',
            'parentID',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'children' => SchemaDefinition::TYPE_ID,
            'title' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'classes' => SchemaDefinition::TYPE_STRING,
            'target' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'objectID' => SchemaDefinition::TYPE_ID,
            'parentID' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'children',
            'classes'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'children' => $this->translationAPI->__('Menu item children items', 'menus'),
            'title' => $this->translationAPI->__('Menu item title', 'menus'),
            'url' => $this->translationAPI->__('Menu item URL', 'menus'),
            'classes' => $this->translationAPI->__('Menu item classes', 'menus'),
            'target' => $this->translationAPI->__('Menu item target', 'menus'),
            'description' => $this->translationAPI->__('Menu item additional attributes', 'menus'),
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
        /** @var MenuItem */
        $menuItem = $resultItem;
        switch ($fieldName) {
            case 'children':
                return array_keys($this->menuItemRuntimeRegistry->getMenuItemChildren($typeResolver->getID($menuItem)));
            case 'title':
            case 'url':
            case 'classes':
            case 'target':
            case 'description':
            case 'objectID':
            case 'parentID':
                return $menuItem->$fieldName;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'children':
                return MenuItemTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
