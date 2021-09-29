<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\Menus\ModuleProcessors\MenuFilterInputContainerModuleProcessor;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;
use PoPSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected MenuObjectTypeResolver $menuObjectTypeResolver;
    protected MenuTypeAPIInterface $menuTypeAPI;

    #[Required]
    public function autowireRootObjectTypeFieldResolver(
        IntScalarTypeResolver $intScalarTypeResolver,
        MenuObjectTypeResolver $menuObjectTypeResolver,
        MenuTypeAPIInterface $menuTypeAPI,
    ): void {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
        $this->menuTypeAPI = $menuTypeAPI;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'menu',
            'menus',
            'menuCount',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'menu' => $this->translationAPI->__('Get a menu', 'menus'),
            'menus' => $this->translationAPI->__('Get all menus', 'menus'),
            'menuCount' => $this->translationAPI->__('Count the number of menus', 'menus'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'menu' => $this->menuObjectTypeResolver,
            'menus' => $this->menuObjectTypeResolver,
            'menuCount' => $this->intScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'menus' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            'menuCount' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'menu':
                return [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'id',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ID,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('The ID of the menu', 'menus'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ];
        }
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'menus' => [MenuFilterInputContainerModuleProcessor::class, MenuFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MENUS],
            'menuCount' => [MenuFilterInputContainerModuleProcessor::class, MenuFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MENUCOUNT],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
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
        switch ($fieldName) {
            case 'menu':
                // Validate the ID exists
                $menuID = $fieldArgs['id'];
                if ($this->menuTypeAPI->getMenu($menuID) !== null) {
                    return $menuID;
                }
                return null;
        }

        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'menus':
                return $this->menuTypeAPI->getMenus($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'menuCount':
                return $this->menuTypeAPI->getMenuCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
