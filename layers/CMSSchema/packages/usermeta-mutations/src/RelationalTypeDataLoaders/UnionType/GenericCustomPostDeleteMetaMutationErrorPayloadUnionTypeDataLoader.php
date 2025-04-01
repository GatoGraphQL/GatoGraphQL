<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericUserDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver $genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver(): GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver = $genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericUserDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
