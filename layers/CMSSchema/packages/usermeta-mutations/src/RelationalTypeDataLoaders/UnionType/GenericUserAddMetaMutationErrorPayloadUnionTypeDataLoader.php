<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericUserAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericUserAddMetaMutationErrorPayloadUnionTypeResolver $genericUserAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserAddMetaMutationErrorPayloadUnionTypeResolver(): GenericUserAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserAddMetaMutationErrorPayloadUnionTypeResolver = $genericUserAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericUserAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
