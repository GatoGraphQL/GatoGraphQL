<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\PageMutations\TypeResolvers\UnionType\RootUpdatePageMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdatePageMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdatePageMutationErrorPayloadUnionTypeResolver $rootUpdatePageMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdatePageMutationErrorPayloadUnionTypeResolver(RootUpdatePageMutationErrorPayloadUnionTypeResolver $rootUpdatePageMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdatePageMutationErrorPayloadUnionTypeResolver = $rootUpdatePageMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdatePageMutationErrorPayloadUnionTypeResolver(): RootUpdatePageMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdatePageMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdatePageMutationErrorPayloadUnionTypeResolver */
            $rootUpdatePageMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdatePageMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdatePageMutationErrorPayloadUnionTypeResolver = $rootUpdatePageMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdatePageMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdatePageMutationErrorPayloadUnionTypeResolver();
    }
}
