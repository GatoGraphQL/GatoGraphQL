<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\RootUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateUserMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateUserMutationErrorPayloadUnionTypeResolver $rootUpdateUserMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateUserMutationErrorPayloadUnionTypeResolver(): RootUpdateUserMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateUserMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateUserMutationErrorPayloadUnionTypeResolver */
            $rootUpdateUserMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateUserMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateUserMutationErrorPayloadUnionTypeResolver = $rootUpdateUserMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateUserMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateUserMutationErrorPayloadUnionTypeResolver();
    }
}
