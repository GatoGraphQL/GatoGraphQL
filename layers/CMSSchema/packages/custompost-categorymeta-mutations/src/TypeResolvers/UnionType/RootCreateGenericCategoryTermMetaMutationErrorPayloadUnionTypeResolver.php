<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootCreateCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateGenericCategoryTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
