<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\UserMetaMutations\TypeResolvers\UnionType\GenericUserSetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericUserSetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericUserSetMetaMutationErrorPayloadUnionTypeResolver $genericUserSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericUserSetMetaMutationErrorPayloadUnionTypeResolver(): GenericUserSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericUserSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericUserSetMetaMutationErrorPayloadUnionTypeResolver */
            $genericUserSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericUserSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericUserSetMetaMutationErrorPayloadUnionTypeResolver = $genericUserSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericUserSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericUserSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
