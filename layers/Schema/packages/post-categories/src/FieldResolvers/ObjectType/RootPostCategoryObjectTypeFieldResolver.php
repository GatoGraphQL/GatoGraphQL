<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Categories\ComponentConfiguration;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\PostCategories\ModuleProcessors\PostCategoryFilterInputContainerModuleProcessor;
use PoPSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class RootPostCategoryObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;
    protected PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver;
    protected PostCategoryTypeAPIInterface $postCategoryTypeAPI;

    #[Required]
    public function autowireRootPostCategoryObjectTypeFieldResolver(
        IntScalarTypeResolver $intScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
        PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver,
        PostCategoryTypeAPIInterface $postCategoryTypeAPI,
    ): void {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
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
            'postCategory',
            'postCategoryBySlug',
            'postCategories',
            'postCategoryCount',
            'postCategoryNames',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'postCategory':
            case 'postCategoryBySlug':
            case 'postCategories':
                return $this->postCategoryObjectTypeResolver;
        }
        $types = [
            'postCategoryCount' => $this->intScalarTypeResolver,
            'postCategoryNames' => $this->stringScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'postCategoryCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'postCategories',
            'postCategoryNames'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'postCategory' => $this->translationAPI->__('Post category with a specific ID', 'post-categories'),
            'postCategoryBySlug' => $this->translationAPI->__('Post category with a specific slug', 'post-categories'),
            'postCategories' => $this->translationAPI->__('Post categories', 'post-categories'),
            'postCategoryCount' => $this->translationAPI->__('Number of post categories', 'post-categories'),
            'postCategoryNames' => $this->translationAPI->__('Names of the post categories', 'post-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'postCategories' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORIES],
            'postCategoryCount' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORYCOUNT],
            'postCategoryNames' => [PostCategoryFilterInputContainerModuleProcessor::class, PostCategoryFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CATEGORIES],
            'postCategory' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            'postCategoryBySlug' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'postCategories':
            case 'postCategoryNames':
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
            case 'postCategories':
            case 'postCategoryNames':
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
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'postCategory':
            case 'postCategoryBySlug':
                if ($categories = $this->postCategoryTypeAPI->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $categories[0];
                }
                return null;
            case 'postCategories':
                return $this->postCategoryTypeAPI->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'postCategoryNames':
                return $this->postCategoryTypeAPI->getCategories($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'postCategoryCount':
                return $this->postCategoryTypeAPI->getCategoryCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
