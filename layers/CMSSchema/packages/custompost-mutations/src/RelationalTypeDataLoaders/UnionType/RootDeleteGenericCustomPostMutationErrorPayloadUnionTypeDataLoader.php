<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver(): RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver = $rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootDeleteGenericCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
