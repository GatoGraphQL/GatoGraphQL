<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PostCategoryMutations\RelationalTypeDataLoaders\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver extends AbstractPostCategoriesMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader(RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader = $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader(): RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader */
            $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader = $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetCategoriesOnPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting categories on a custom post', 'postcategory-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
