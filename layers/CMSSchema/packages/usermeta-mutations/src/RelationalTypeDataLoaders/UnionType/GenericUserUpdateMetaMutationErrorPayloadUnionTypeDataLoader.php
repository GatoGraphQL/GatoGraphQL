<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericUserUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver $genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver(): GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver = $genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericUserUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
