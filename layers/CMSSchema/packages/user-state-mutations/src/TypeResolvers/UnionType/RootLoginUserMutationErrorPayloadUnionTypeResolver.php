<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserStateMutations\RelationalTypeDataLoaders\UnionType\RootLoginUserMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootLoginUserMutationErrorPayloadUnionTypeResolver extends AbstractUserStateMutationErrorPayloadUnionTypeResolver
{
    private ?RootLoginUserMutationErrorPayloadUnionTypeDataLoader $rootLoginUserMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootLoginUserMutationErrorPayloadUnionTypeDataLoader(): RootLoginUserMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootLoginUserMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootLoginUserMutationErrorPayloadUnionTypeDataLoader */
            $rootLoginUserMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootLoginUserMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootLoginUserMutationErrorPayloadUnionTypeDataLoader = $rootLoginUserMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootLoginUserMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootLoginUserMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when logging a user in', 'user-state-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootLoginUserMutationErrorPayloadUnionTypeDataLoader();
    }
}
