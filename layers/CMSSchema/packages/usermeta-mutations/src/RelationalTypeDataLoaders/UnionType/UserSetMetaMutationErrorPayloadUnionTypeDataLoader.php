<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\UserSetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class UserSetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?UserSetMetaMutationErrorPayloadUnionTypeResolver $userSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getUserSetMetaMutationErrorPayloadUnionTypeResolver(): UserSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->userSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var UserSetMetaMutationErrorPayloadUnionTypeResolver */
            $userSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(UserSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->userSetMetaMutationErrorPayloadUnionTypeResolver = $userSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->userSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getUserSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
