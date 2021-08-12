<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoPSchema\Categories\ModuleProcessors\CategoryFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

abstract class AbstractChildCategoryFieldResolver extends AbstractQueryableFieldResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getFieldNamesToResolve(): array
    {
        return [
            'childCategories',
            'childCategoryCount',
            'childCategoryNames',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'childCategories' => SchemaDefinition::TYPE_ID,
            'childCategoryCount' => SchemaDefinition::TYPE_INT,
            'childCategoryNames' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'childCategoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'childCategories',
            'childCategoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'childCategories' => $this->translationAPI->__('Post categories', 'child-categories'),
            'childCategoryCount' => $this->translationAPI->__('Number of post categories', 'child-categories'),
            'childCategoryNames' => $this->translationAPI->__('Names of the post categories', 'child-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'childCategories':
            case 'childCategoryCount':
            case 'childCategoryNames':
                return array_merge(
                    $schemaFieldArgs,
                    $this->getFieldArgumentsSchemaDefinitions($typeResolver, $fieldName)
                );
        }
        return $schemaFieldArgs;
    }

    public function enableOrderedSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'childCategories':
            case 'childCategoryCount':
            case 'childCategoryNames':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'childCategories' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            'childCategoryCount' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT],
            'childCategoryNames' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
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
        $category = $resultItem;
        $categoryTypeAPI = $this->getTypeAPI();
        $query = [
            'parent-id' => $typeResolver->getID($category),
        ];
        switch ($fieldName) {
            case 'childCategories':
            case 'childCategoryNames':
                $query['limit'] = ComponentConfiguration::getCategoryListDefaultLimit();
                $options = [
                    'return-type' => $fieldName === 'childCategories' ? ReturnTypes::IDS : ReturnTypes::NAMES,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $categoryTypeAPI->getCategories($query, $options);
            case 'childCategoryCount':
                $options = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $categoryTypeAPI->getCategoryCount($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'childCategory':
            case 'childCategoryBySlug':
            case 'childCategories':
                return $this->getTypeResolverClass();
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
