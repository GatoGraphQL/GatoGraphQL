<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\ObjectType\FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = null;

    final protected function getFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader(): FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader
    {
        if ($this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader === null) {
            /** @var FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader */
            $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(FeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader::class);
            $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader = $featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
        }
        return $this->featuredImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'FeaturedImageIsNotSupportedByCustomPostTypeErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "Setting the featured image is not supported by the custom post type"', 'custompostmedia-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getFeaturedImageIsNotSupportedByCustomPostTypeErrorPayloadObjectTypeDataLoader();
    }
}
