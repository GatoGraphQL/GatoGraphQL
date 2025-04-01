<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType\GenericTagAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericTagAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericTagAddMetaMutationErrorPayloadUnionTypeResolver $genericTagAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericTagAddMetaMutationErrorPayloadUnionTypeResolver(): GenericTagAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericTagAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericTagAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericTagAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericTagAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericTagAddMetaMutationErrorPayloadUnionTypeResolver = $genericTagAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericTagAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericTagAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
