<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\PostCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostCategoryAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader $postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader(): PostCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = $postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostCategoryAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
