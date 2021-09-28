<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers\ObjectType;

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
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\ModuleProcessors\PostFilterInputContainerModuleProcessor;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

abstract class AbstractPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected PostObjectTypeResolver $postObjectTypeResolver;
    protected PostTypeAPIInterface $postTypeAPI;

    #[Required]
    public function autowireAbstractPostObjectTypeFieldResolver(
        IntScalarTypeResolver $intScalarTypeResolver,
        PostObjectTypeResolver $postObjectTypeResolver,
        PostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->postObjectTypeResolver = $postObjectTypeResolver;
        $this->postTypeAPI = $postTypeAPI;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'posts',
            'postCount',
            'postsForAdmin',
            'postCountForAdmin',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'postsForAdmin',
            'postCountForAdmin',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'posts':
            case 'postsForAdmin':
                return $this->postObjectTypeResolver;
        }
        $types = [
            'postCount' => $this->intScalarTypeResolver,
            'postCountForAdmin' => $this->intScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'postCount',
            'postCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'posts',
            'postsForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'posts' => $this->translationAPI->__('Posts', 'pop-posts'),
            'postCount' => $this->translationAPI->__('Number of posts', 'pop-posts'),
            'postsForAdmin' => $this->translationAPI->__('[Unrestricted] Posts', 'pop-posts'),
            'postCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of posts', 'pop-posts'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'posts' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_POSTS],
            'postCount' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_POSTCOUNT],
            'postsForAdmin' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPOSTS],
            'postCountForAdmin' => [PostFilterInputContainerModuleProcessor::class, PostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPOSTCOUNT],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'posts':
            case 'postsForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getPostListDefaultLimit(),
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
            case 'posts':
            case 'postsForAdmin':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getPostListMaxLimit(),
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
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        return [];
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
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            $this->getQuery($objectTypeResolver, $object, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'posts':
            case 'postsForAdmin':
                return $this->postTypeAPI->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'postCount':
            case 'postCountForAdmin':
                return $this->postTypeAPI->getPostCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
