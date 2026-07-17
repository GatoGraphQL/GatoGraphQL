<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\ObjectType\MediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MediaItemDoesNotSupportTrashErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?MediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader $mediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = null;

    final protected function getMediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader(): MediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader
    {
        if ($this->mediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader === null) {
            /** @var MediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader */
            $mediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(MediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader::class);
            $this->mediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader = $mediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
        }
        return $this->mediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MediaItemDoesNotSupportTrashErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The media item does not support being sent to the trash"', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaItemDoesNotSupportTrashErrorPayloadObjectTypeDataLoader();
    }
}
