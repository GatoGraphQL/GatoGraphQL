<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\UserDeleteMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserDeleteMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?UserDeleteMutationErrorPayloadUnionTypeResolver $userDeleteMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserDeleteMutationErrorPayloadUnionTypeResolver(): UserDeleteMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userDeleteMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserDeleteMutationErrorPayloadUnionTypeResolver */
            $userDeleteMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserDeleteMutationErrorPayloadUnionTypeResolver::class);
            $this->userDeleteMutationErrorPayloadUnionTypeResolver = $userDeleteMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userDeleteMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getUserDeleteMutationErrorPayloadUnionTypeResolver();
    }
}
