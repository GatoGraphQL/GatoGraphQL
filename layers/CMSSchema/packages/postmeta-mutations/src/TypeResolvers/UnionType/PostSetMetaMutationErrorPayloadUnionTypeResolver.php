<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostSetMetaMutationErrorPayloadUnionTypeDataLoader $postSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostSetMetaMutationErrorPayloadUnionTypeDataLoader(): PostSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $postSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postSetMetaMutationErrorPayloadUnionTypeDataLoader = $postSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
