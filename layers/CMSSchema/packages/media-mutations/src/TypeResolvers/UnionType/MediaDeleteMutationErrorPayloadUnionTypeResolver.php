<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType\MediaDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MediaDeleteMutationErrorPayloadUnionTypeResolver extends AbstractDeleteMediaItemMutationErrorPayloadUnionTypeResolver
{
    private ?MediaDeleteMutationErrorPayloadUnionTypeDataLoader $mediaDeleteMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getMediaDeleteMutationErrorPayloadUnionTypeDataLoader(): MediaDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->mediaDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var MediaDeleteMutationErrorPayloadUnionTypeDataLoader */
            $mediaDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(MediaDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->mediaDeleteMutationErrorPayloadUnionTypeDataLoader = $mediaDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->mediaDeleteMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MediaDeleteMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting an attachment (nested mutations)', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMediaDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}
