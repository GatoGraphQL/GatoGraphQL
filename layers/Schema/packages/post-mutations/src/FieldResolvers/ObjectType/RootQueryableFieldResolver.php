<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootTypeResolver;
use PoPSchema\PostMutations\ModuleProcessors\PostMutationFilterInputContainerModuleProcessor;
use PoPSchema\Posts\ComponentConfiguration;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\UserState\FieldResolvers\ObjectType\UserStateFieldResolverTrait;

class RootQueryableFieldResolver extends AbstractQueryableFieldResolver
{
    use UserStateFieldResolverTrait;
    use WithLimitFieldArgResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'myPosts',
            'myPostCount',
            'myPost',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'myPostCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'myPostCount' => SchemaTypeModifiers::NON_NULLABLE,
            'myPosts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'myPosts' => $this->translationAPI->__('Posts by the logged-in user', 'post-mutations'),
            'myPostCount' => $this->translationAPI->__('Number of posts by the logged-in user', 'post-mutations'),
            'myPost' => $this->translationAPI->__('Post with a specific ID', 'post-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'myPost' => [PostMutationFilterInputContainerModuleProcessor::class, PostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYPOST],
            'myPosts' => [PostMutationFilterInputContainerModuleProcessor::class, PostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYPOSTS],
            'myPostCount' => [PostMutationFilterInputContainerModuleProcessor::class, PostMutationFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYPOSTCOUNT],
            default => parent::getFieldDataFilteringModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'myPosts':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getPostListDefaultLimit(),
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($objectTypeResolver, $fieldName);
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
            case 'myPosts':
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
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): array {
        switch ($fieldName) {
            case 'myPost':
            case 'myPosts':
            case 'myPostCount':
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
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            $this->getQuery($objectTypeResolver, $resultItem, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'myPostCount':
                return $postTypeAPI->getPostCount($query);

            case 'myPosts':
                return $postTypeAPI->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'myPost':
                if ($posts = $postTypeAPI->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'myPosts':
            case 'myPost':
                return PostTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
