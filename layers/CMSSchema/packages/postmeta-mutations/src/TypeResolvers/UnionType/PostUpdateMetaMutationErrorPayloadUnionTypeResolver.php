<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader $postUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $postUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $postUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
