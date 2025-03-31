<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootSetTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetPostTagTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetTagTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader $rootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetPostTagTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post\'s tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetPostTagTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
