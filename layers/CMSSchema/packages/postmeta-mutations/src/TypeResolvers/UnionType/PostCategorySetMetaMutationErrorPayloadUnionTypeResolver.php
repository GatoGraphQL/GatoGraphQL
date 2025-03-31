<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostSetMetaMutationErrorPayloadUnionTypeDataLoader $postCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostSetMetaMutationErrorPayloadUnionTypeDataLoader(): PostSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader = $postCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post\'s custom post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
