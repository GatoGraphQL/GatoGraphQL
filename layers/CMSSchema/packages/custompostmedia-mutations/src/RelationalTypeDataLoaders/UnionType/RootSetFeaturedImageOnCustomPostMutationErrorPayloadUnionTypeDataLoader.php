<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver(RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver $rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver = $rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver(): RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootSetFeaturedImageOnCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
