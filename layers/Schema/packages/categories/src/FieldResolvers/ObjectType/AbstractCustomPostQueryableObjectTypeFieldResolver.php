<?php

declare(strict_types=1);

namespace PoPSchema\Categories\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractInterface;
use PoPSchema\Categories\ModuleProcessors\CategoryFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

abstract class AbstractCustomPostQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver implements CategoryAPIRequestedContractInterface
{
    use WithLimitFieldArgResolverTrait;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        EngineInterface $engine,
        ModuleProcessorManagerInterface $moduleProcessorManager,
        protected StringScalarTypeResolver $stringScalarTypeResolver,
        protected IntScalarTypeResolver $intScalarTypeResolver,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
            $schemaDefinitionService,
            $engine,
            $moduleProcessorManager,
        );
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
        switch ($fieldName) {
            case 'categories':
                return $this->getCategoryTypeResolver();
        }
        $types = [
            'categoryCount' => $this->intScalarTypeResolver,
            'categoryNames' => $this->stringScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'categoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'categories',
            'categoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'categories' => $this->translationAPI->__('Categories added to this custom post', 'pop-categories'),
            'categoryCount' => $this->translationAPI->__('Number of categories added to this custom post', 'pop-categories'),
            'categoryNames' => $this->translationAPI->__('Names of the categories added to this custom post', 'pop-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
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

    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'categories':
            case 'categoryNames':
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
        array $fieldArgs = [],
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
