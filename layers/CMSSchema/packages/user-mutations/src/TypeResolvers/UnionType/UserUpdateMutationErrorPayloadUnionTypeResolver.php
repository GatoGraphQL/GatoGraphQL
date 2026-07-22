<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\UnionType\UserUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserUpdateMutationErrorPayloadUnionTypeResolver extends AbstractUpdateUserMutationErrorPayloadUnionTypeResolver
{
    private ?UserUpdateMutationErrorPayloadUnionTypeDataLoader $userUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getUserUpdateMutationErrorPayloadUnionTypeDataLoader(): UserUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->userUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var UserUpdateMutationErrorPayloadUnionTypeDataLoader */
            $userUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(UserUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->userUpdateMutationErrorPayloadUnionTypeDataLoader = $userUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->userUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a user (nested mutations)', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
