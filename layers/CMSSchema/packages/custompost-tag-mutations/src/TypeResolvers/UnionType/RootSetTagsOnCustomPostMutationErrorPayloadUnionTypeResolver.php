<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostTagMutations\RelationalTypeDataLoaders\UnionType\RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractGenericTagsMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader $rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader */
            $rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetTagsOnCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting tags on a custom post', 'posttag-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetTagsOnCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
