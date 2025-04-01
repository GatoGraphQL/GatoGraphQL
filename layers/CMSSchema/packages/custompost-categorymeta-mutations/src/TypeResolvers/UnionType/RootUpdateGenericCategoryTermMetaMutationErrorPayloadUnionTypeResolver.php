<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostCategoryMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateGenericCategoryTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
