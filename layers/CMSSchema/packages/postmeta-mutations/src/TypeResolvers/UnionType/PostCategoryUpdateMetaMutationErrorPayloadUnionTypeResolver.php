<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategoryUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader $postCategoryUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCategoryUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $postCategoryUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategoryUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
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
