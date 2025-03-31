<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteGenericCategoryTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericCategoryTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
