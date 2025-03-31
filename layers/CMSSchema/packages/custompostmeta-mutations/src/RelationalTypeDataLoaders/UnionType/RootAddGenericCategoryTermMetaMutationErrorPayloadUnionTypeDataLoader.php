<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver $rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver(): RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver */
            $rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver = $rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddGenericCustomPostMetaMutationErrorPayloadUnionTypeResolver();
    }
}
