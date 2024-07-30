<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootCreateCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\UnionType\RootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateGenericCategoryMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateCategoryMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader(RootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader(): RootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateCategoryMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a category', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateGenericCategoryMutationErrorPayloadUnionTypeDataLoader();
    }
}
