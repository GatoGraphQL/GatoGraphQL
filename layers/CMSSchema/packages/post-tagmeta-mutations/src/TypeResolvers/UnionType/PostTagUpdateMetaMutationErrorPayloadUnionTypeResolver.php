<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType\PostTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostTagUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractTagUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader $postTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): PostTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $postTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $postTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostTagUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a post\'s tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
