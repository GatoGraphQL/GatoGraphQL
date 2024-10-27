<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\FieldResolvers\ObjectType;

use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByOneofInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemSortInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemPaginationInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemsFilterInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
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

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?MediaItemByOneofInputObjectTypeResolver $mediaItemByOneofInputObjectTypeResolver = null;
    private ?RootMediaItemsFilterInputObjectTypeResolver $rootMediaItemsFilterInputObjectTypeResolver = null;
    private ?RootMediaItemPaginationInputObjectTypeResolver $rootMediaItemPaginationInputObjectTypeResolver = null;
    private ?MediaItemSortInputObjectTypeResolver $mediaItemSortInputObjectTypeResolver = null;

    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        if ($this->mediaTypeAPI === null) {
            /** @var MediaTypeAPIInterface */
            $mediaTypeAPI = $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
            $this->mediaTypeAPI = $mediaTypeAPI;
        }
        return $this->mediaTypeAPI;
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
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        if ($this->mediaObjectTypeResolver === null) {
            /** @var MediaObjectTypeResolver */
            $mediaObjectTypeResolver = $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
            $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
        }
        return $this->mediaObjectTypeResolver;
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
    final protected function getRootMediaItemsFilterInputObjectTypeResolver(): RootMediaItemsFilterInputObjectTypeResolver
    {
        if ($this->rootMediaItemsFilterInputObjectTypeResolver === null) {
            /** @var RootMediaItemsFilterInputObjectTypeResolver */
            $rootMediaItemsFilterInputObjectTypeResolver = $this->instanceManager->getInstance(RootMediaItemsFilterInputObjectTypeResolver::class);
            $this->rootMediaItemsFilterInputObjectTypeResolver = $rootMediaItemsFilterInputObjectTypeResolver;
        }
        return $this->rootMediaItemsFilterInputObjectTypeResolver;
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
    final protected function getMediaItemSortInputObjectTypeResolver(): MediaItemSortInputObjectTypeResolver
    {
        if ($this->mediaItemSortInputObjectTypeResolver === null) {
            /** @var MediaItemSortInputObjectTypeResolver */
            $mediaItemSortInputObjectTypeResolver = $this->instanceManager->getInstance(MediaItemSortInputObjectTypeResolver::class);
            $this->mediaItemSortInputObjectTypeResolver = $mediaItemSortInputObjectTypeResolver;
        }
        return $this->mediaItemSortInputObjectTypeResolver;
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
            'mediaItem',
            'mediaItems',
            'mediaItemCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'mediaItem' => $this->__('Get a media item', 'media'),
            'mediaItems' => $this->__('Get the media items', 'media'),
            'mediaItemCount' => $this->__('Number of media items', 'media'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'mediaItems',
            'mediaItem'
                => $this->getMediaObjectTypeResolver(),
            'mediaItemCount'
                => $this->getIntScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'mediaItems' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            'mediaItemCount' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'mediaItem' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getMediaItemByOneofInputObjectTypeResolver(),
                ]
            ),
            'mediaItems' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMediaItemsFilterInputObjectTypeResolver(),
                    'pagination' => $this->getRootMediaItemPaginationInputObjectTypeResolver(),
                    'sort' => $this->getMediaItemSortInputObjectTypeResolver(),
                ]
            ),
            'mediaItemCount' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'filter' => $this->getRootMediaItemsFilterInputObjectTypeResolver(),
                ]
            ),
            default => $fieldArgNameTypeResolvers,
        };
    }

    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['mediaItem' => 'by'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'mediaItemCount':
                return $this->getMediaTypeAPI()->getMediaItemCount($query);
            case 'mediaItems':
                return $this->getMediaTypeAPI()->getMediaItems($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'mediaItem':
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
}
