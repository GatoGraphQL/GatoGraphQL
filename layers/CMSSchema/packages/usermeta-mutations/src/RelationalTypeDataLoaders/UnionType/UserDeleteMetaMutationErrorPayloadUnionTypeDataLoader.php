<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\UserDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?UserDeleteMetaMutationErrorPayloadUnionTypeResolver $userDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserDeleteMetaMutationErrorPayloadUnionTypeResolver(): UserDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $userDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->userDeleteMetaMutationErrorPayloadUnionTypeResolver = $userDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getUserDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
