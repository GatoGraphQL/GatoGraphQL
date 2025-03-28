<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader $rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeletePostCategoryTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post\'s category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeletePostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
