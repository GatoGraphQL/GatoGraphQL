<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MediaItemDoesNotExistErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader $mediaItemDoesNotExistErrorPayloadObjectTypeDataLoader = null;

    final protected function getMediaItemDoesNotExistErrorPayloadObjectTypeDataLoader(): MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader
    {
        if ($this->mediaItemDoesNotExistErrorPayloadObjectTypeDataLoader === null) {
            /** @var MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader */
            $mediaItemDoesNotExistErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(MediaItemDoesNotExistErrorPayloadObjectTypeDataLoader::class);
            $this->mediaItemDoesNotExistErrorPayloadObjectTypeDataLoader = $mediaItemDoesNotExistErrorPayloadObjectTypeDataLoader;
        }
        return $this->mediaItemDoesNotExistErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MediaItemDoesNotExistErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The requested media item does not exist"', 'custompostmedia-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaItemDoesNotExistErrorPayloadObjectTypeDataLoader();
    }
}
