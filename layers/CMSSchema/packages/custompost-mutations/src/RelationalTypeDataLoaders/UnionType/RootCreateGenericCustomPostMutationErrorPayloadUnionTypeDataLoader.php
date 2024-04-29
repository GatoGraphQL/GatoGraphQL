<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreateGenericCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver(RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver(): RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver = $rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreateGenericCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
