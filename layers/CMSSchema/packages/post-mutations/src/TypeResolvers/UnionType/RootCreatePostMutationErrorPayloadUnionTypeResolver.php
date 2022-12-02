<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\RootCreatePostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreatePostMutationErrorPayloadUnionTypeResolver extends AbstractPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreatePostMutationErrorPayloadUnionTypeDataLoader $rootCreatePostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreatePostMutationErrorPayloadUnionTypeDataLoader(RootCreatePostMutationErrorPayloadUnionTypeDataLoader $rootCreatePostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreatePostMutationErrorPayloadUnionTypeDataLoader = $rootCreatePostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreatePostMutationErrorPayloadUnionTypeDataLoader(): RootCreatePostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootCreatePostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootCreatePostMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootCreatePostMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'RootCreatePostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreatePostMutationErrorPayloadUnionTypeDataLoader();
    }
}
