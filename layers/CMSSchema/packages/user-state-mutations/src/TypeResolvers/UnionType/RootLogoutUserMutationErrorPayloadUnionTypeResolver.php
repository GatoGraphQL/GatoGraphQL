<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLogoutUserMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootLogoutUserMutationErrorPayloadUnionTypeResolver extends AbstractUserStateMutationErrorPayloadUnionTypeResolver
{
    private ?RootLogoutUserMutationErrorPayloadUnionTypeDataLoader $rootLogoutUserMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootLogoutUserMutationErrorPayloadUnionTypeDataLoader(RootLogoutUserMutationErrorPayloadUnionTypeDataLoader $rootLogoutUserMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootLogoutUserMutationErrorPayloadUnionTypeDataLoader = $rootLogoutUserMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootLogoutUserMutationErrorPayloadUnionTypeDataLoader(): RootLogoutUserMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootLogoutUserMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootLogoutUserMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootLogoutUserMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'RootLogoutUserMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when logging a user out', 'user-state-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootLogoutUserMutationErrorPayloadUnionTypeDataLoader();
    }
}
