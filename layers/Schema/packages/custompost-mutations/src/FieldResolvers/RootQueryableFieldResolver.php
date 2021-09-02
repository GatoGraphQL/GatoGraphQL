<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\CustomPostMutations\ModuleProcessors\CustomPostMutationFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\ComponentConfiguration;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\UserState\FieldResolvers\UserStateFieldResolverTrait;

class RootQueryableFieldResolver extends AbstractQueryableFieldResolver
{
    use UserStateFieldResolverTrait;
    use WithLimitFieldArgResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'myCustomPosts',
            'myCustomPostCount',
            'myCustomPost',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'myCustomPosts' => SchemaDefinition::TYPE_ID,
            'myCustomPostCount' => SchemaDefinition::TYPE_INT,
            'myCustomPost' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'myCustomPostCount' => SchemaTypeModifiers::NON_NULLABLE,
            'myCustomPosts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'myCustomPosts' => $this->translationAPI->__('Custom posts by the logged-in user', 'custompost-mutations'),
            'myCustomPostCount' => $this->translationAPI->__('Number of custom posts by the logged-in user', 'custompost-mutations'),
            'myCustomPost' => $this->translationAPI->__('Custom post with a specific ID', 'custompost-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'myCustomPost' => [CustomPostMutationFilterInputContainerModuleProcessor::class, CustomPostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOST],
            'myCustomPosts' => [CustomPostMutationFilterInputContainerModuleProcessor::class, CustomPostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTS],
            'myCustomPostCount' => [CustomPostMutationFilterInputContainerModuleProcessor::class, CustomPostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCUSTOMPOSTCOUNT],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(TypeResolverInterface $typeResolver, string $fieldName): array
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
        return parent::getFieldDataFilteringDefaultValues($typeResolver, $fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        TypeResolverInterface $typeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $typeResolver,
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $postTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($typeResolver, $fieldName, $fieldArgs),
            $this->getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'myCustomPostCount':
                return $postTypeAPI->getCustomPostCount($query);

            case 'myCustomPosts':
                return $postTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'myCustomPost':
                if ($customPosts = $postTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $customPosts[0];
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'myCustomPosts':
            case 'myCustomPost':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
