<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategorySetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\PostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCategorySetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategorySetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader $postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader(): PostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader = $postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategorySetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostCategorySetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostCategorySetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
