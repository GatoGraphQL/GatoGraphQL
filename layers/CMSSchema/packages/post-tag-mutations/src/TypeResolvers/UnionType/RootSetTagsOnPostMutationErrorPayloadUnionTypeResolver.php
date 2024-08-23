<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetTagsOnPostMutationErrorPayloadUnionTypeResolver extends AbstractPostTagsMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader(RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader = $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader(): RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader */
            $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader = $rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetTagsOnPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting tags on a post', 'posttag-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
