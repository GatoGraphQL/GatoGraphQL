<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootSetCategoryTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetPostTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetCategoryTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader $rootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetPostTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post\'s category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetPostTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
