<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader $postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
