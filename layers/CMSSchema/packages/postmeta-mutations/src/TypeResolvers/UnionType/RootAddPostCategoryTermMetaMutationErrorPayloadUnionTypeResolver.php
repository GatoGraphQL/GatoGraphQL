<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\RootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddPostTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootAddCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader $rootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddPostTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a post\'s category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddPostTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
