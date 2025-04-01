<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\UserAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?UserAddMetaMutationErrorPayloadUnionTypeResolver $userAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserAddMetaMutationErrorPayloadUnionTypeResolver(): UserAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserAddMetaMutationErrorPayloadUnionTypeResolver */
            $userAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->userAddMetaMutationErrorPayloadUnionTypeResolver = $userAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getUserAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
