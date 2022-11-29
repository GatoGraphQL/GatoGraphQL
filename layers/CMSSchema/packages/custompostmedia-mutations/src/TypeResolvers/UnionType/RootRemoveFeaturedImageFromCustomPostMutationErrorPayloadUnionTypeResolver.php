<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostMediaMutationErrorPayloadUnionTypeResolver
{
    private ?RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader(RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when removing a featured image from a custom post', 'custompostmedia-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
