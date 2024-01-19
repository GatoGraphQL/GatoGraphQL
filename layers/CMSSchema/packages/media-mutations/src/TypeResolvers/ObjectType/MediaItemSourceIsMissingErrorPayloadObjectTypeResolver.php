<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\MediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MediaItemSourceIsMissingErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?MediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader $mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader = null;

    final public function setMediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader(MediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader $mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader): void
    {
        $this->mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader = $mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader;
    }
    final protected function getMediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader(): MediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader
    {
        if ($this->mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader === null) {
            /** @var MediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader */
            $mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(MediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader::class);
            $this->mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader = $mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader;
        }
        return $this->mediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MediaItemSourceIsMissingErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The media item source is missing"', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaItemSourceIsMissingErrorPayloadObjectTypeDataLoader();
    }
}
