<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteUserMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteGenericUserMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a user', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericUserMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
