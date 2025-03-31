<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $postCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCustomPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
