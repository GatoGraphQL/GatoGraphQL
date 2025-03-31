<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType\PostTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostTagDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractTagDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader $postTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): PostTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $postTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $postTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostTagDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post\'s tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
