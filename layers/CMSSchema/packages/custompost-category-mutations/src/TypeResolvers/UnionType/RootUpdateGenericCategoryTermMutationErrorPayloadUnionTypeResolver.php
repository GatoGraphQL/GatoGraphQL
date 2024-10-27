<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootUpdateCategoryTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCategoryTermMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateGenericCategoryTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericCategoryTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
