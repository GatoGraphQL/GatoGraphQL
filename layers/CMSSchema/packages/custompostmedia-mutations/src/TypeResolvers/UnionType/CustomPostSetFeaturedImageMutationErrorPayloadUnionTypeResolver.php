<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostMediaMutationErrorPayloadUnionTypeResolver
{
    private ?CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader $customPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader(): CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader */
            $customPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader::class);
            $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader = $customPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostSetFeaturedImageMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting a featured to a custom post (using nested mutations)', 'custompostmedia-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeDataLoader();
    }
}
