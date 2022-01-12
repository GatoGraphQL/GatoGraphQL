<?php

declare(strict_types=1);

namespace PoPSchema\PostMutations\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPSchema\PostMutations\TypeResolvers\InputObjectType\RootMyPostsFilterInputObjectTypeResolver;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPSchema\Posts\TypeResolvers\InputObjectType\PostByInputObjectTypeResolver;
use PoPSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\UserState\FieldResolvers\ObjectType\UserStateObjectTypeFieldResolverTrait;

class RootQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use UserStateObjectTypeFieldResolverTrait;
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostByInputObjectTypeResolver $postByInputObjectTypeResolver = null;
    private ?RootMyPostsFilterInputObjectTypeResolver $rootMyPostsFilterInputObjectTypeResolver = null;
    private ?PostPaginationInputObjectTypeResolver $postPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setPostObjectTypeResolver(PostObjectTypeResolver $postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        return $this->postObjectTypeResolver ??= $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    final public function setPostTypeAPI(PostTypeAPIInterface $postTypeAPI): void
    {
        $this->postTypeAPI = $postTypeAPI;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        return $this->postTypeAPI ??= $this->instanceManager->getInstance(PostTypeAPIInterface::class);
    }
    final public function setPostByInputObjectTypeResolver(PostByInputObjectTypeResolver $postByInputObjectTypeResolver): void
    {
        $this->postByInputObjectTypeResolver = $postByInputObjectTypeResolver;
    }
    final protected function getPostByInputObjectTypeResolver(): PostByInputObjectTypeResolver
    {
        return $this->postByInputObjectTypeResolver ??= $this->instanceManager->getInstance(PostByInputObjectTypeResolver::class);
    }
    final public function setRootMyPostsFilterInputObjectTypeResolver(RootMyPostsFilterInputObjectTypeResolver $rootMyPostsFilterInputObjectTypeResolver): void
    {
        $this->rootMyPostsFilterInputObjectTypeResolver = $rootMyPostsFilterInputObjectTypeResolver;
    }
    final protected function getRootMyPostsFilterInputObjectTypeResolver(): RootMyPostsFilterInputObjectTypeResolver
    {
        return $this->rootMyPostsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootMyPostsFilterInputObjectTypeResolver::class);
    }
    final public function setPostPaginationInputObjectTypeResolver(PostPaginationInputObjectTypeResolver $postPaginationInputObjectTypeResolver): void
    {
        $this->postPaginationInputObjectTypeResolver = $postPaginationInputObjectTypeResolver;
    }
    final protected function getPostPaginationInputObjectTypeResolver(): PostPaginationInputObjectTypeResolver
    {
        return $this->postPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(PostPaginationInputObjectTypeResolver::class);
    }
    final public function setCustomPostSortInputObjectTypeResolver(CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver): void
    {
        $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        return $this->customPostSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
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
            'myPosts',
            'myPostCount',
            'myPost',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'myPosts',
            'myPost'
                => $this->getPostObjectTypeResolver(),
            'myPostCount'
                => $this->getIntScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'myPostCount' => SchemaTypeModifiers::NON_NULLABLE,
            'myPosts' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myPosts' => $this->__('Posts by the logged-in user', 'post-mutations'),
            'myPostCount' => $this->__('Number of posts by the logged-in user', 'post-mutations'),
            'myPost' => $this->__('Retrieve a single post by the logged-in user', 'post-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'myPost' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS
            ],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'myPost' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getPostByInputObjectTypeResolver(),
                ]
            ),
            'myPosts' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyPostsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getPostPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCustomPostSortInputObjectTypeResolver(),
                ]
            ),
            'myPostCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyPostsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['myPost' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): array {
        return match ($fieldName) {
            'myPost',
            'myPosts',
            'myPostCount'
                => [
                    'authors' => [App::getState('current-user-id')],
                ],
            default
                => [],
        };
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
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            $this->getQuery($objectTypeResolver, $object, $fieldName, $fieldArgs)
        );
        switch ($fieldName) {
            case 'myPostCount':
                return $this->getPostTypeAPI()->getPostCount($query);

            case 'myPosts':
                return $this->getPostTypeAPI()->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'myPost':
                if ($posts = $this->getPostTypeAPI()->getPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $posts[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
