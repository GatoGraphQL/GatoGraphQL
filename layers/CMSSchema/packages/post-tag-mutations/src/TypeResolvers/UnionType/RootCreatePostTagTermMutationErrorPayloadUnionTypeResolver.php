<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootCreateTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\RootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreatePostTagTermMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateTagTermMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader $rootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader(): RootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader */
            $rootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader = $rootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreatePostTagTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a post tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreatePostTagTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
