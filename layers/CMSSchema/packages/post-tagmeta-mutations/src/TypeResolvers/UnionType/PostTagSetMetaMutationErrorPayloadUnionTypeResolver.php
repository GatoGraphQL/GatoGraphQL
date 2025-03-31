<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType\PostTagSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostTagSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractTagSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostTagSetMetaMutationErrorPayloadUnionTypeDataLoader $postTagSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostTagSetMetaMutationErrorPayloadUnionTypeDataLoader(): PostTagSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postTagSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostTagSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $postTagSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostTagSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postTagSetMetaMutationErrorPayloadUnionTypeDataLoader = $postTagSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postTagSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostTagSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post\'s tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
