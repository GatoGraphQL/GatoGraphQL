<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractTagAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType\PostTagAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostTagAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractTagAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostTagAddMetaMutationErrorPayloadUnionTypeDataLoader $postTagAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostTagAddMetaMutationErrorPayloadUnionTypeDataLoader(): PostTagAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postTagAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostTagAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $postTagAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostTagAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postTagAddMetaMutationErrorPayloadUnionTypeDataLoader = $postTagAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postTagAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostTagAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a post\'s tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
