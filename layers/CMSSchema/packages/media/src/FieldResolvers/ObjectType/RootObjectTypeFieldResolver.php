<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemSortInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemPaginationInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemsFilterInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?MediaTypeAPIInterface $mediaTypeAPI = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?MediaObjectTypeResolver $mediaObjectTypeResolver = null;
    private ?MediaItemByInputObjectTypeResolver $mediaItemByInputObjectTypeResolver = null;
    private ?RootMediaItemsFilterInputObjectTypeResolver $rootMediaItemsFilterInputObjectTypeResolver = null;
    private ?RootMediaItemPaginationInputObjectTypeResolver $rootMediaItemPaginationInputObjectTypeResolver = null;
    private ?MediaItemSortInputObjectTypeResolver $mediaItemSortInputObjectTypeResolver = null;

    final public function setMediaTypeAPI(MediaTypeAPIInterface $mediaTypeAPI): void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    final protected function getMediaTypeAPI(): MediaTypeAPIInterface
    {
        return $this->mediaTypeAPI ??= $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setMediaObjectTypeResolver(MediaObjectTypeResolver $mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        return $this->mediaObjectTypeResolver ??= $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    final public function setMediaItemByInputObjectTypeResolver(MediaItemByInputObjectTypeResolver $mediaItemByInputObjectTypeResolver): void
    {
        $this->mediaItemByInputObjectTypeResolver = $mediaItemByInputObjectTypeResolver;
    }
    final protected function getMediaItemByInputObjectTypeResolver(): MediaItemByInputObjectTypeResolver
    {
        return $this->mediaItemByInputObjectTypeResolver ??= $this->instanceManager->getInstance(MediaItemByInputObjectTypeResolver::class);
    }
    final public function setRootMediaItemsFilterInputObjectTypeResolver(RootMediaItemsFilterInputObjectTypeResolver $rootMediaItemsFilterInputObjectTypeResolver): void
    {
        $this->rootMediaItemsFilterInputObjectTypeResolver = $rootMediaItemsFilterInputObjectTypeResolver;
    }
    final protected function getRootMediaItemsFilterInputObjectTypeResolver(): RootMediaItemsFilterInputObjectTypeResolver
    {
        return $this->rootMediaItemsFilterInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootMediaItemsFilterInputObjectTypeResolver::class);
    }
    final public function setRootMediaItemPaginationInputObjectTypeResolver(RootMediaItemPaginationInputObjectTypeResolver $rootMediaItemPaginationInputObjectTypeResolver): void
    {
        $this->rootMediaItemPaginationInputObjectTypeResolver = $rootMediaItemPaginationInputObjectTypeResolver;
    }
    final protected function getRootMediaItemPaginationInputObjectTypeResolver(): RootMediaItemPaginationInputObjectTypeResolver
    {
        return $this->rootMediaItemPaginationInputObjectTypeResolver ??= $this->instanceManager->getInstance(RootMediaItemPaginationInputObjectTypeResolver::class);
    }
    final public function setMediaItemSortInputObjectTypeResolver(MediaItemSortInputObjectTypeResolver $mediaItemSortInputObjectTypeResolver): void
    {
        $this->mediaItemSortInputObjectTypeResolver = $mediaItemSortInputObjectTypeResolver;
    }
    final protected function getMediaItemSortInputObjectTypeResolver(): MediaItemSortInputObjectTypeResolver
    {
        return $this->mediaItemSortInputObjectTypeResolver ??= $this->instanceManager->getInstance(MediaItemSortInputObjectTypeResolver::class);
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

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        return match ($fieldName) {
            'mediaItem' => array_merge(
                $fieldArgNameTypeResolvers,
                [
                    'by' => $this->getMediaItemByInputObjectTypeResolver(),
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

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}
