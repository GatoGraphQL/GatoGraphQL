<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootCreateCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateCategoryTermMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader(): RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateGenericCategoryTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
