<?php

declare(strict_types=1);

namespace PoPSchema\Menus\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Menus\Facades\MenuTypeAPIFacade;
use PoPSchema\Menus\ModuleProcessors\MenuFilterInputContainerModuleProcessor;
use PoPSchema\Menus\TypeResolvers\MenuTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'menu',
            'menus',
            'menuCount',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'menu' => $this->translationAPI->__('Get a menu', 'menus'),
            'menus' => $this->translationAPI->__('Get all menus', 'menus'),
            'menuCount' => $this->translationAPI->__('Count the number of menus', 'menus'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'menu' => SchemaDefinition::TYPE_ID,
            'menus' => SchemaDefinition::TYPE_ID,
            'menuCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'menus' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            'menuCount' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
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
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'menus' => [MenuFilterInputContainerModuleProcessor::class, MenuFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MENUS],
            'menuCount' => [MenuFilterInputContainerModuleProcessor::class, MenuFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MENUCOUNT],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
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
        switch ($fieldName) {
            case 'menu':
                // Validate the ID exists
                $menuID = $fieldArgs['id'];
                if ($menuTypeAPI->getMenu($menuID) !== null) {
                    return $menuID;
                }
                return null;
            case 'menus':
                $options = array_merge(
                    [
                        'return-type' => ReturnTypes::IDS,
                    ],
                    $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs)
                );
                return $menuTypeAPI->getMenus([], $options);
            case 'menuCount':
                $options = $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs);
                return $menuTypeAPI->getMenuCount([], $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'menu':
            case 'menus':
                return MenuTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
