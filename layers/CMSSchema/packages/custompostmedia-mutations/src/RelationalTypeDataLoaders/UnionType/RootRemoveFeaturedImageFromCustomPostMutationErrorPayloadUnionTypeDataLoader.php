<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver(): RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
