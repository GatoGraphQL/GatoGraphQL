<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\RootMyMediaItemsFilterInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByOneofInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemSortInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemPaginationInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
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
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?MediaItemByOneofInputObjectTypeResolver $mediaItemByOneofInputObjectTypeResolver = null;
    private ?RootMyMediaItemsFilterInputObjectTypeResolver $rootMyMediaItemsFilterInputObjectTypeResolver = null;
    private ?RootMediaItemPaginationInputObjectTypeResolver $rootMediaItemPaginationInputObjectTypeResolver = null;
    private ?MediaItemSortInputObjectTypeResolver $mediaItemSortInputObjectTypeResolver = null;
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
    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        if ($this->mediaObjectTypeResolver === null) {
            /** @var MediaObjectTypeResolver */
            $mediaObjectTypeResolver = $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
            $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
        }
        return $this->mediaObjectTypeResolver;
    }
    final public function setMediaItemByOneofInputObjectTypeResolver(MediaItemByOneofInputObjectTypeResolver $mediaItemByOneofInputObjectTypeResolver): void
    {
        $this->mediaItemByOneofInputObjectTypeResolver = $mediaItemByOneofInputObjectTypeResolver;
    }
    final protected function getMediaItemByOneofInputObjectTypeResolver(): MediaItemByOneofInputObjectTypeResolver
    {
        if ($this->mediaItemByOneofInputObjectTypeResolver === null) {
            /** @var MediaItemByOneofInputObjectTypeResolver */
            $mediaItemByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(MediaItemByOneofInputObjectTypeResolver::class);
            $this->mediaItemByOneofInputObjectTypeResolver = $mediaItemByOneofInputObjectTypeResolver;
        }
        return $this->mediaItemByOneofInputObjectTypeResolver;
    }
    final public function setRootMyMediaItemsFilterInputObjectTypeResolver(RootMyMediaItemsFilterInputObjectTypeResolver $rootMyMediaItemsFilterInputObjectTypeResolver): void
    {
        $this->rootMyMediaItemsFilterInputObjectTypeResolver = $rootMyMediaItemsFilterInputObjectTypeResolver;
    }
    final protected function getRootMyMediaItemsFilterInputObjectTypeResolver(): RootMyMediaItemsFilterInputObjectTypeResolver
    {
        if ($this->rootMyMediaItemsFilterInputObjectTypeResolver === null) {
            /** @var RootMyMediaItemsFilterInputObjectTypeResolver */
            $rootMyMediaItemsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootMyMediaItemsFilterInputObjectTypeResolver::class);
            $this->rootMyMediaItemsFilterInputObjectTypeResolver = $rootMyMediaItemsFilterInputObjectTypeResolver;
        }
        return $this->rootMyMediaItemsFilterInputObjectTypeResolver;
    }
    final public function setRootMediaItemPaginationInputObjectTypeResolver(RootMediaItemPaginationInputObjectTypeResolver $rootMediaItemPaginationInputObjectTypeResolver): void
    {
        $this->rootMediaItemPaginationInputObjectTypeResolver = $rootMediaItemPaginationInputObjectTypeResolver;
    }
    final protected function getRootMediaItemPaginationInputObjectTypeResolver(): RootMediaItemPaginationInputObjectTypeResolver
    {
        if ($this->rootMediaItemPaginationInputObjectTypeResolver === null) {
            /** @var RootMediaItemPaginationInputObjectTypeResolver */
            $rootMediaItemPaginationInputObjectTypeResolver = $this->instanceManager->getInstance(RootMediaItemPaginationInputObjectTypeResolver::class);
            $this->rootMediaItemPaginationInputObjectTypeResolver = $rootMediaItemPaginationInputObjectTypeResolver;
        }
        return $this->rootMediaItemPaginationInputObjectTypeResolver;
    }
    final public function setMediaItemSortInputObjectTypeResolver(MediaItemSortInputObjectTypeResolver $mediaItemSortInputObjectTypeResolver): void
    {
        $this->mediaItemSortInputObjectTypeResolver = $mediaItemSortInputObjectTypeResolver;
    }
    final protected function getMediaItemSortInputObjectTypeResolver(): MediaItemSortInputObjectTypeResolver
    {
        if ($this->mediaItemSortInputObjectTypeResolver === null) {
            /** @var MediaItemSortInputObjectTypeResolver */
            $mediaItemSortInputObjectTypeResolver = $this->instanceManager->getInstance(MediaItemSortInputObjectTypeResolver::class);
            $this->mediaItemSortInputObjectTypeResolver = $mediaItemSortInputObjectTypeResolver;
        }
        return $this->mediaItemSortInputObjectTypeResolver;
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
            'myMediaItem',
            'myMediaItemCount',
            'myMediaItems',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'myMediaItemCount'
                => $this->getIntScalarTypeResolver(),
            'myMediaItems',
            'myMediaItem'
                => $this->getMediaObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'myMediaItemCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'myMediaItems'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myMediaItem' => $this->__('Media item by the logged-in user on the site with a specific ID', 'media-mutations'),
            'myMediaItemCount' => $this->__('Number of media items by the logged-in user on the site', 'media-mutations'),
            'myMediaItems' => $this->__('Media items by the logged-in user on the site', 'media-mutations'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'myMediaItem' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getMediaItemByOneofInputObjectTypeResolver(),
                ]
            ),
            'myMediaItems' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyMediaItemsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getRootMediaItemPaginationInputObjectTypeResolver(),
                    'sort' => $this->getMediaItemSortInputObjectTypeResolver(),
                ]
            ),
            'myMediaItemCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMyMediaItemsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['myMediaItem' => 'by'] => SchemaTypeModifiers::MANDATORY,
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
            case 'myMediaItemCount':
                return $this->getMediaTypeAPI()->getMediaItemCount($query);
            case 'myMediaItems':
                return $this->getMediaTypeAPI()->getMediaItems($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'myMediaItem':
                if ($mediaItems = $this->getMediaTypeAPI()->getMediaItems($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $mediaItems[0];
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
