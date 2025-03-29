<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootSetCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetPostCategoryTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post\'s category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetPostCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
