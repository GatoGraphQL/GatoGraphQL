<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\MediaDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class MediaDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?MediaDeleteMutationErrorPayloadUnionTypeResolver $mediaDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getMediaDeleteMutationErrorPayloadUnionTypeResolver(): MediaDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->mediaDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var MediaDeleteMutationErrorPayloadUnionTypeResolver */
            $mediaDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(MediaDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->mediaDeleteMutationErrorPayloadUnionTypeResolver = $mediaDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->mediaDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getMediaDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
