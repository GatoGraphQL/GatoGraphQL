<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddPostCategoryTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a post\'s category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
