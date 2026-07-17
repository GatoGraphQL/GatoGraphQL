<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader $mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = null;

    final protected function getMediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader(): MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader
    {
        if ($this->mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader === null) {
            /** @var MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader */
            $mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(MediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader::class);
            $this->mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader = $mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
        }
        return $this->mediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MediaItemHasAlreadyBeenTrashedErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The media item has already been sent to the trash"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaItemHasAlreadyBeenTrashedErrorPayloadObjectTypeDataLoader();
    }
}
