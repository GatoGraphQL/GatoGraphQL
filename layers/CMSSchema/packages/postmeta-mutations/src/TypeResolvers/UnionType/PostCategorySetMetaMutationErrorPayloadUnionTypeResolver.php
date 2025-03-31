<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategorySetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategorySetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostSetMetaMutationErrorPayloadUnionTypeDataLoader $postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostSetMetaMutationErrorPayloadUnionTypeDataLoader(): PostSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = $postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
