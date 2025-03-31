<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\PostAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCategoryAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?PostAddMetaMutationErrorPayloadUnionTypeDataLoader $postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostAddMetaMutationErrorPayloadUnionTypeDataLoader(): PostAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader = $postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postCategoryAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a post\'s category term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
