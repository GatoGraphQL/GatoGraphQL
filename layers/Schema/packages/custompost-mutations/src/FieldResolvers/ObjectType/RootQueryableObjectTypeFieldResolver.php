<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CustomPostMutations\ModuleProcessors\CustomPostMutationFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\UserState\FieldResolvers\ObjectType\UserStateObjectTypeFieldResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class RootQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use UserStateObjectTypeFieldResolverTrait;
    use WithLimitFieldArgResolverTrait;

    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected CustomPostTypeAPIInterface $postTypeAPI;

    #[Required]
    public function autowireRootQueryableObjectTypeFieldResolver(
        IntScalarTypeResolver $intScalarTypeResolver,
        CustomPostTypeAPIInterface $postTypeAPI,
    ): void {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->postTypeAPI = $postTypeAPI;
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
            'myCustomPosts',
            'myCustomPostCount',
            'myCustomPost',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'myCustomPosts' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'myCustomPost' => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            'myCustomPostCount' => $this->intScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'myCustomPostCount' => SchemaTypeModifiers::NON_NULLABLE,
            'myCustomPosts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myCustomPosts' => $this->translationAPI->__('Custom posts by the logged-in user', 'custompost-mutations'),
            'myCustomPostCount' => $this->translationAPI->__('Number of custom posts by the logged-in user', 'custompost-mutations'),
            'myCustomPost' => $this->translationAPI->__('Custom post with a specific ID', 'custompost-mutations'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'myCustomPost' => [CustomPostMutationFilterInputContainerModuleProcessor::class, CustomPostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOST],
            'myCustomPosts' => [CustomPostMutationFilterInputContainerModuleProcessor::class, CustomPostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS],
            'myCustomPostCount' => [CustomPostMutationFilterInputContainerModuleProcessor::class, CustomPostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'myCustomPosts':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getCustomPostListDefaultLimit(),
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
            case 'myCustomPosts':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getCustomPostListMaxLimit(),
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
        switch ($fieldName) {
            case 'myCustomPost':
            case 'myCustomPosts':
            case 'myCustomPostCount':
                $vars = ApplicationState::getVars();
                return [
                    'authors' => [$vars['global-userstate']['current-user-id']],
                ];
        }
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
            case 'myCustomPostCount':
                return $this->postTypeAPI->getCustomPostCount($query);

            case 'myCustomPosts':
                return $this->postTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'myCustomPost':
                if ($customPosts = $this->postTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $customPosts[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
