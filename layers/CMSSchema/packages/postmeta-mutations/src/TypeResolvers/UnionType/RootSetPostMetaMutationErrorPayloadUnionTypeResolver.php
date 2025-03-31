<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootSetCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetPostMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetPostMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetPostMetaMutationErrorPayloadUnionTypeDataLoader $rootSetPostMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetPostMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetPostMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetPostMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetPostMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetPostMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetPostMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetPostMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetPostMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetPostMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetPostMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetPostMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
