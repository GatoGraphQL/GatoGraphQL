<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMediaMutations\RelationalTypeDataLoaders\UnionType\PostSetTagsMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostSetTagsMutationErrorPayloadUnionTypeResolver extends AbstractPostTagsMutationErrorPayloadUnionTypeResolver
{
    private ?PostSetTagsMutationErrorPayloadUnionTypeDataLoader $postSetTagsMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setPostSetTagsMutationErrorPayloadUnionTypeDataLoader(PostSetTagsMutationErrorPayloadUnionTypeDataLoader $postSetTagsMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->postSetTagsMutationErrorPayloadUnionTypeDataLoader = $postSetTagsMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getPostSetTagsMutationErrorPayloadUnionTypeDataLoader(): PostSetTagsMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var PostSetTagsMutationErrorPayloadUnionTypeDataLoader */
        return $this->postSetTagsMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(PostSetTagsMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'PostSetTagsMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting tags on a custom post (using nested mutations)', 'posttag-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostSetTagsMutationErrorPayloadUnionTypeDataLoader();
    }
}
