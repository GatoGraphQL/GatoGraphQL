<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType\MediaUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MediaUpdateMutationErrorPayloadUnionTypeResolver extends AbstractUpdateMediaItemMutationErrorPayloadUnionTypeResolver
{
    private ?MediaUpdateMutationErrorPayloadUnionTypeDataLoader $mediaUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setMediaUpdateMutationErrorPayloadUnionTypeDataLoader(MediaUpdateMutationErrorPayloadUnionTypeDataLoader $mediaUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->mediaUpdateMutationErrorPayloadUnionTypeDataLoader = $mediaUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getMediaUpdateMutationErrorPayloadUnionTypeDataLoader(): MediaUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->mediaUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var MediaUpdateMutationErrorPayloadUnionTypeDataLoader */
            $mediaUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(MediaUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->mediaUpdateMutationErrorPayloadUnionTypeDataLoader = $mediaUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->mediaUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MediaUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating the metadata for an attachment (nested mutations)', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
