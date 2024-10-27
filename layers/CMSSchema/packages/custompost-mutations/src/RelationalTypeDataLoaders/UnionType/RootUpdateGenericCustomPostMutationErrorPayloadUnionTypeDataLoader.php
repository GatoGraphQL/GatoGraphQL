<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateGenericCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
