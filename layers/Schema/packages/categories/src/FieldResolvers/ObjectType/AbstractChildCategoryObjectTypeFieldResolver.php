<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers\ObjectType;

use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;
use PoPSchema\Categories\ModuleProcessors\CategoryFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

abstract class AbstractChildCategoryObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use CategoryAPIRequestedContractTrait;
    use WithLimitFieldArgResolverTrait;

    public function getFieldNamesToResolve(): array
    {
        return [
            'childCategories',
            'childCategoryCount',
            'childCategoryNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'childCategory':
                return $this->getCategoryTypeResolverClass();
        }
        $types = [
            'childCategoryCount' => IntScalarTypeResolver::class,
            'childCategoryNames' => StringScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'childCategoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'childCategories',
            'childCategoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'childCategories' => $this->translationAPI->__('Post categories', 'child-categories'),
            'childCategoryCount' => $this->translationAPI->__('Number of post categories', 'child-categories'),
            'childCategoryNames' => $this->translationAPI->__('Names of the post categories', 'child-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'childCategories' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            'childCategoryCount' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORYCOUNT],
            'childCategoryNames' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CHILDCATEGORIES],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'childCategories':
            case 'childCategoryNames':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getCategoryListDefaultLimit(),
                ];
        }
        return parent::getFieldFilterInputDefaultValues($objectTypeResolver, $fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'childCategories':
            case 'childCategoryNames':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getCategoryListMaxLimit(),
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors[] = $maybeError;
                }
                break;
        }
        return $errors;
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
        $category = $object;
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            [
                'parent-id' => $objectTypeResolver->getID($category),
            ]
        );
        switch ($fieldName) {
            case 'childCategories':
                return $categoryTypeAPI->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'childCategoryNames':
                return $categoryTypeAPI->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'childCategoryCount':
                return $categoryTypeAPI->getCategoryCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
