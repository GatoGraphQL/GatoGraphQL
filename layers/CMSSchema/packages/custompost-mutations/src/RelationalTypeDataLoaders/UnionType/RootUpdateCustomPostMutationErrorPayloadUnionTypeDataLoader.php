<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver $rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver(RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver $rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver = $rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver(): RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootUpdateCustomPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootUpdateCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
