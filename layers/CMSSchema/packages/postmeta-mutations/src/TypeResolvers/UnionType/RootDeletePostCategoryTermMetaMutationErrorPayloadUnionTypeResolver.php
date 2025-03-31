<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CategoryMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeletePostTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader $rootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeletePostTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post\'s category term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeletePostTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
