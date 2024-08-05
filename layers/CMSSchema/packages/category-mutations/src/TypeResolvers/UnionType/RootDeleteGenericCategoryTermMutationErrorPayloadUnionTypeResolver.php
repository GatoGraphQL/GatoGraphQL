<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootDeleteCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCategoryTermMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader(RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader(): RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteGenericCategoryTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
