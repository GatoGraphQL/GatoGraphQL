<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\ComponentProcessors\CommonCustomPostFilterInputContainerComponentProcessor;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostSortInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootMyPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeAPIs\PostTypeAPIInterface;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostByOneofInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostPaginationInputObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;

class RootQueryableObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?PostObjectTypeResolver $postObjectTypeResolver = null;
    private ?PostTypeAPIInterface $postTypeAPI = null;
    private ?PostByOneofInputObjectTypeResolver $postByOneofInputObjectTypeResolver = null;
    private ?RootMyPostsFilterInputObjectTypeResolver $rootMyPostsFilterInputObjectTypeResolver = null;
    private ?PostPaginationInputObjectTypeResolver $postPaginationInputObjectTypeResolver = null;
    private ?CustomPostSortInputObjectTypeResolver $customPostSortInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        if ($this->postObjectTypeResolver === null) {
            /** @var PostObjectTypeResolver */
            $postObjectTypeResolver = $this->instanceManager->getInstance(PostObjectTypeResolver::class);
            $this->postObjectTypeResolver = $postObjectTypeResolver;
        }
        return $this->postObjectTypeResolver;
    }
    final protected function getPostTypeAPI(): PostTypeAPIInterface
    {
        if ($this->postTypeAPI === null) {
            /** @var PostTypeAPIInterface */
            $postTypeAPI = $this->instanceManager->getInstance(PostTypeAPIInterface::class);
            $this->postTypeAPI = $postTypeAPI;
        }
        return $this->postTypeAPI;
    }
    final protected function getPostByOneofInputObjectTypeResolver(): PostByOneofInputObjectTypeResolver
    {
        if ($this->postByOneofInputObjectTypeResolver === null) {
            /** @var PostByOneofInputObjectTypeResolver */
            $postByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(PostByOneofInputObjectTypeResolver::class);
            $this->postByOneofInputObjectTypeResolver = $postByOneofInputObjectTypeResolver;
        }
        return $this->postByOneofInputObjectTypeResolver;
    }
    final protected function getRootMyPostsFilterInputObjectTypeResolver(): RootMyPostsFilterInputObjectTypeResolver
    {
        if ($this->rootMyPostsFilterInputObjectTypeResolver === null) {
            /** @var RootMyPostsFilterInputObjectTypeResolver */
            $rootMyPostsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootMyPostsFilterInputObjectTypeResolver::class);
            $this->rootMyPostsFilterInputObjectTypeResolver = $rootMyPostsFilterInputObjectTypeResolver;
        }
        return $this->rootMyPostsFilterInputObjectTypeResolver;
    }
    final protected function getPostPaginationInputObjectTypeResolver(): PostPaginationInputObjectTypeResolver
    {
        if ($this->postPaginationInputObjectTypeResolver === null) {
            /** @var PostPaginationInputObjectTypeResolver */
            $postPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(PostPaginationInputObjectTypeResolver::class);
            $this->postPaginationInputObjectTypeResolver = $postPaginationInputObjectTypeResolver;
        }
        return $this->postPaginationInputObjectTypeResolver;
    }
    final protected function getCustomPostSortInputObjectTypeResolver(): CustomPostSortInputObjectTypeResolver
    {
        if ($this->customPostSortInputObjectTypeResolver === null) {
            /** @var CustomPostSortInputObjectTypeResolver */
            $customPostSortInputObjectTypeResolver = $this->instanceManager->getInstance(CustomPostSortInputObjectTypeResolver::class);
            $this->customPostSortInputObjectTypeResolver = $customPostSortInputObjectTypeResolver;
        }
        return $this->customPostSortInputObjectTypeResolver;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        if ($this->userLoggedInCheckpoint === null) {
            /** @var UserLoggedInCheckpoint */
            $userLoggedInCheckpoint = $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
            $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
        }
        return $this->userLoggedInCheckpoint;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'myPost' => new Component(
                CommonCustomPostFilterInputContainerComponentProcessor::class,
                CommonCustomPostFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOSTSTATUS
            ),
            default => parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'myPost' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getPostByOneofInputObjectTypeResolver(),
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
     * @return array<string,mixed>
     */
    protected function getQuery(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        return match ($fieldDataAccessor->getFieldName()) {
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor),
            $this->getQuery($objectTypeResolver, $object, $fieldDataAccessor)
        );
        switch ($fieldDataAccessor->getFieldName()) {
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }

    /**
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );
        $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
        return $validationCheckpoints;
    }
}
