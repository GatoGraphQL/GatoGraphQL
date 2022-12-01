<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostSetCategoriesMutationErrorPayloadUnionTypeResolver extends AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver
{
    private ?PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader $postSetCategoriesMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setPostSetCategoriesMutationErrorPayloadUnionTypeDataLoader(PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader $postSetCategoriesMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->postSetCategoriesMutationErrorPayloadUnionTypeDataLoader = $postSetCategoriesMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getPostSetCategoriesMutationErrorPayloadUnionTypeDataLoader(): PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader */
        return $this->postSetCategoriesMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(PostSetCategoriesMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'PostSetCategoriesMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting categories on a custom post (using nested mutations)', 'postcategory-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostSetCategoriesMutationErrorPayloadUnionTypeDataLoader();
    }
}
