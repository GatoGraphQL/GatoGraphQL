<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractCategoryUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\PostCategoryUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCategoryUpdateMutationErrorPayloadUnionTypeResolver extends AbstractCategoryUpdateMutationErrorPayloadUnionTypeResolver
{
    private ?PostCategoryUpdateMutationErrorPayloadUnionTypeDataLoader $postCategoryUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostCategoryUpdateMutationErrorPayloadUnionTypeDataLoader(): PostCategoryUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategoryUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostCategoryUpdateMutationErrorPayloadUnionTypeDataLoader */
            $postCategoryUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostCategoryUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategoryUpdateMutationErrorPayloadUnionTypeDataLoader = $postCategoryUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategoryUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostCategoryUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a post category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostCategoryUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
