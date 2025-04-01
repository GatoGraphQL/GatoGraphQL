<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\RootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
