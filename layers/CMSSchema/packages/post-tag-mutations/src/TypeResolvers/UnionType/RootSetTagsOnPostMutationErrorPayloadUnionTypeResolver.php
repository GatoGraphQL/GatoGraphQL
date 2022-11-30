<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType\RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader;
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
        /** @var RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'RootSetTagsOnPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting tags on a custom post', 'posttag-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetTagsOnPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
