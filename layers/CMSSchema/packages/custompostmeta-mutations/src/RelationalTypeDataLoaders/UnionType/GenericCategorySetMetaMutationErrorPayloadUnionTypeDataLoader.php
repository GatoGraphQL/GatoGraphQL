<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCustomPostSetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver $genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver(): GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver */
            $genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver = $genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCustomPostSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
