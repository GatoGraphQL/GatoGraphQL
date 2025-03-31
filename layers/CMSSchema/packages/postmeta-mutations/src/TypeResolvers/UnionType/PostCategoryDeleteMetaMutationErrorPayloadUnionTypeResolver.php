<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\PostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategoryDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader $postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): PostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostCategoryDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostCategoryDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
