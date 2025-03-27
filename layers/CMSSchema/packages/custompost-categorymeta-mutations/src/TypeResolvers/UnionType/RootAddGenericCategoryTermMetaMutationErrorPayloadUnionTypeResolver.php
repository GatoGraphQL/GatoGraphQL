<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddGenericCategoryTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
