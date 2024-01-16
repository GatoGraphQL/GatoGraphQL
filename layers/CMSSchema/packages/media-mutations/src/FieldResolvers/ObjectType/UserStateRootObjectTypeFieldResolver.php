<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\RootMyCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\ComponentProcessors\SingleCommentFilterInputContainerComponentProcessor;
use PoPCMSSchema\Comments\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentByOneofInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentPaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
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

class UserStateRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;
    private ?CommentByOneofInputObjectTypeResolver $commentByOneofInputObjectTypeResolver = null;
    private ?RootMyCommentsFilterInputObjectTypeResolver $rootMyCommentsFilterInputObjectTypeResolver = null;
    private ?RootCommentPaginationInputObjectTypeResolver $rootCommentPaginationInputObjectTypeResolver = null;
    private ?CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver = null;
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        if ($this->mediaTypeAPI === null) {
            /** @var MediaTypeAPIInterface */
            $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
            $this->mediaTypeAPI = $mediaTypeAPI;
        }
        return $this->mediaTypeAPI;
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }
    final public function setCommentByOneofInputObjectTypeResolver(CommentByOneofInputObjectTypeResolver $commentByOneofInputObjectTypeResolver): void
    {
        $this->commentByOneofInputObjectTypeResolver = $commentByOneofInputObjectTypeResolver;
    }
    final protected function getCommentByOneofInputObjectTypeResolver(): CommentByOneofInputObjectTypeResolver
    {
        if ($this->commentByOneofInputObjectTypeResolver === null) {
            /** @var CommentByOneofInputObjectTypeResolver */
            $commentByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(CommentByOneofInputObjectTypeResolver::class);
            $this->commentByOneofInputObjectTypeResolver = $commentByOneofInputObjectTypeResolver;
        }
        return $this->commentByOneofInputObjectTypeResolver;
    }
    final public function setRootMyCommentsFilterInputObjectTypeResolver(RootMyCommentsFilterInputObjectTypeResolver $rootMyCommentsFilterInputObjectTypeResolver): void
    {
        $this->rootMyCommentsFilterInputObjectTypeResolver = $rootMyCommentsFilterInputObjectTypeResolver;
    }
    final protected function getRootMyCommentsFilterInputObjectTypeResolver(): RootMyCommentsFilterInputObjectTypeResolver
    {
        if ($this->rootMyCommentsFilterInputObjectTypeResolver === null) {
            /** @var RootMyCommentsFilterInputObjectTypeResolver */
            $rootMyCommentsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootMyCommentsFilterInputObjectTypeResolver::class);
            $this->rootMyCommentsFilterInputObjectTypeResolver = $rootMyCommentsFilterInputObjectTypeResolver;
        }
        return $this->rootMyCommentsFilterInputObjectTypeResolver;
    }
    final public function setRootCommentPaginationInputObjectTypeResolver(RootCommentPaginationInputObjectTypeResolver $rootCommentPaginationInputObjectTypeResolver): void
    {
        $this->rootCommentPaginationInputObjectTypeResolver = $rootCommentPaginationInputObjectTypeResolver;
    }
    final protected function getRootCommentPaginationInputObjectTypeResolver(): RootCommentPaginationInputObjectTypeResolver
    {
        if ($this->rootCommentPaginationInputObjectTypeResolver === null) {
            /** @var RootCommentPaginationInputObjectTypeResolver */
            $rootCommentPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(RootCommentPaginationInputObjectTypeResolver::class);
            $this->rootCommentPaginationInputObjectTypeResolver = $rootCommentPaginationInputObjectTypeResolver;
        }
        return $this->rootCommentPaginationInputObjectTypeResolver;
    }
    final public function setCommentSortInputObjectTypeResolver(CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver): void
    {
        $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
    }
    final protected function getCommentSortInputObjectTypeResolver(): CommentSortInputObjectTypeResolver
    {
        if ($this->commentSortInputObjectTypeResolver === null) {
            /** @var CommentSortInputObjectTypeResolver */
            $commentSortInputObjectTypeResolver = $this->instanceManager->getInstance(CommentSortInputObjectTypeResolver::class);
            $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
        }
        return $this->commentSortInputObjectTypeResolver;
    }
    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
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
            'myComment',
            'myCommentCount',
            'myComments',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'myCommentCount'
                => $this->getIntScalarTypeResolver(),
            'myComments',
            'myComment'
                => $this->getCommentObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'myCommentCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'myComments'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myComment' => $this->__('Comment by the logged-in user on the site with a specific ID', 'pop-comments'),
            'myCommentCount' => $this->__('Number of comments by the logged-in user on the site', 'pop-comments'),
            'myComments' => $this->__('Comments by the logged-in user on the site', 'pop-comments'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerComponent(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?Component
    {
        return match ($fieldName) {
            'myComment' => new Component(SingleCommentFilterInputContainerComponentProcessor::class, SingleCommentFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_COMMENT_STATUS),
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
            'myComment' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getCommentByOneofInputObjectTypeResolver(),
                ]
            ),
            'myComments' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyCommentsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getRootCommentPaginationInputObjectTypeResolver(),
                    'sort' => $this->getCommentSortInputObjectTypeResolver(),
                ]
            ),
            'myCommentCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyCommentsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['myComment' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
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
            [
                'authors' => [App::getState('current-user-id')],
            ]
        );
        switch ($fieldDataAccessor->getFieldName()) {
            case 'myCommentCount':
                return $this->getMediaTypeAPI()->getCommentCount($query);
            case 'myComments':
                return $this->getMediaTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'myComment':
                if ($comments = $this->getMediaTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $comments[0];
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
