<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteTagTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader $rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeletePostTagTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a post\'s tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeletePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
