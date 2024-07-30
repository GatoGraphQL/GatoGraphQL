<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMutations\TypeResolvers\UnionType\AbstractRootUpdateCategoryMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericCategoryMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCategoryMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader(RootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateCategoryMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a category', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericCategoryMutationErrorPayloadUnionTypeDataLoader();
    }
}
