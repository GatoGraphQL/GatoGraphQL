<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootDeleteCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeletePostCategoryTermMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCategoryTermMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader(RootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader(): RootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader */
            $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeletePostCategoryTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a post category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeletePostCategoryTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
