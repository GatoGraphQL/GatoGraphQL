<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver(): CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver */
            $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver::class);
            $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver = $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver();
    }
}
