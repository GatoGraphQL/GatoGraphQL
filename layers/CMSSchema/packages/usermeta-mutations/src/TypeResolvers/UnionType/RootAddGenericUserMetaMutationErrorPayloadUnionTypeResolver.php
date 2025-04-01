<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractRootAddUserMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\RootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddGenericUserMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootAddUserMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader $rootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader(): RootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader = $rootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddGenericUserMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a user', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddGenericUserMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
