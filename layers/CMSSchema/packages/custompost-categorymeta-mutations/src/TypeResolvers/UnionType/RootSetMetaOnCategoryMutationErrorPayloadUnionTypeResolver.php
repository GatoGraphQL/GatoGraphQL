<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetMetaOnCategoryMutationErrorPayloadUnionTypeResolver extends AbstractGenericCategoriesMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader(): RootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader */
            $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader = $rootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetMetaOnCategoryMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting categories on a custom post', 'postcategory-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetMetaOnCategoryMutationErrorPayloadUnionTypeDataLoader();
    }
}
