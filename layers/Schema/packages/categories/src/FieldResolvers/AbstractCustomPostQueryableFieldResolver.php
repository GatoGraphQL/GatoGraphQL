<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers;

use PoPSchema\Categories\ComponentConfiguration;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Categories\ModuleProcessors\FieldDataloadModuleProcessor;

abstract class AbstractCustomPostQueryableFieldResolver extends AbstractQueryableFieldResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getFieldNamesToResolve(): array
    {
        return [
            'categories',
            'categoryCount',
            'categoryNames',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'categories' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'categoryCount' => SchemaDefinition::TYPE_INT,
            'categoryNames' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'categories',
            'categoryCount',
            'categoryNames',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'categories' => $this->translationAPI->__('Categories added to this custom post', 'pop-categories'),
            'categoryCount' => $this->translationAPI->__('Number of categories added to this custom post', 'pop-categories'),
            'categoryNames' => $this->translationAPI->__('Names of the categories added to this custom post', 'pop-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'categories':
            case 'categoryCount':
            case 'categoryNames':
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
            case 'categories':
            case 'categoryCount':
            case 'categoryNames':
                return false;
        }
        return parent::enableOrderedSchemaFieldArgs($typeResolver, $fieldName);
    }

    protected function getFieldDefaultFilterDataloadingModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        switch ($fieldName) {
            case 'categoryCount':
                return [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYCOUNT];
            case 'categoryNames':
                return [FieldDataloadModuleProcessor::class, FieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_CATEGORYLIST];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
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
        $categoryTypeAPI = $this->getTypeAPI();
        $post = $resultItem;
        switch ($fieldName) {
            case 'categories':
            case 'categoryNames':
                $query = [
                    'limit' => ComponentConfiguration::getCategoryListDefaultLimit(),
                ];
                $options = [
                    'return-type' => $fieldName === 'categories' ? ReturnTypes::IDS : ReturnTypes::NAMES,
                ];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $categoryTypeAPI->getCustomPostCategories(
                    $typeResolver->getID($post),
                    $query,
                    $options
                );
            case 'categoryCount':
                $options = [];
                $query = [];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $categoryTypeAPI->getCustomPostCategoryCount(
                    $typeResolver->getID($post),
                    $query,
                    $options
                );
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'categories':
                return $this->getTypeResolverClass();
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
