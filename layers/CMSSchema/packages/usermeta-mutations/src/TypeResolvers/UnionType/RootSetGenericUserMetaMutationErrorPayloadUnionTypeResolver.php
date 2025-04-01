<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootSetUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetGenericUserMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetUserMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader $rootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetGenericUserMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a user', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetGenericUserMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
