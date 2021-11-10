<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractObjectTypeFieldResolverInterface;
use PoPSchema\Categories\ModuleProcessors\CategoryFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

abstract class AbstractCustomPostQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements CategoryAPIRequestedContractObjectTypeFieldResolverInterface
{
    use WithLimitFieldArgResolverTrait;

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'categories',
            'categoryCount',
            'categoryNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'categories' => $this->getCategoryTypeResolver(),
            'categoryCount' => $this->getIntScalarTypeResolver(),
            'categoryNames' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'categoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'categories',
            'categoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'categories' => $this->getTranslationAPI()->__('Categories added to this custom post', 'pop-categories'),
            'categoryCount' => $this->getTranslationAPI()->__('Number of categories added to this custom post', 'pop-categories'),
            'categoryNames' => $this->getTranslationAPI()->__('Names of the categories added to this custom post', 'pop-categories'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'categories' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORIES],
            'categoryCount' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT],
            'categoryNames' => [CategoryFilterInputContainerModuleProcessor::class, CategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORIES],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        switch ($fieldName) {
            case 'categories':
            case 'categoryNames':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                if ($fieldArgName === $limitFilterInputName) {
                    return ComponentConfiguration::getCategoryListDefaultLimit();
                }
                break;
        }
        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgValue(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'categories':
            case 'categoryNames':
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $categoryTypeAPI = $this->getCategoryTypeAPI();
        $post = $object;
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'categories':
                return $categoryTypeAPI->getCustomPostCategories($objectTypeResolver->getID($post), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'categoryNames':
                return $categoryTypeAPI->getCustomPostCategories($objectTypeResolver->getID($post), $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'categoryCount':
                return $categoryTypeAPI->getCustomPostCategoryCount($objectTypeResolver->getID($post), $query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
