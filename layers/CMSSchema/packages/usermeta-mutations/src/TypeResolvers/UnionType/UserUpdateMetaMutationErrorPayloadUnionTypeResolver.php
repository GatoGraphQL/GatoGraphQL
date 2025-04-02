<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\AbstractUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType\UserUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class UserUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractUserUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?UserUpdateMetaMutationErrorPayloadUnionTypeDataLoader $userUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): UserUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->userUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var UserUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $userUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(UserUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->userUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $userUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->userUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'UserUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a user (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
