<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\RootCreateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootCreateCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootCreateCustomPostMutationErrorPayloadUnionTypeResolver $rootCreateCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootCreateCustomPostMutationErrorPayloadUnionTypeResolver(RootCreateCustomPostMutationErrorPayloadUnionTypeResolver $rootCreateCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootCreateCustomPostMutationErrorPayloadUnionTypeResolver = $rootCreateCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootCreateCustomPostMutationErrorPayloadUnionTypeResolver(): RootCreateCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootCreateCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootCreateCustomPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootCreateCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootCreateCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
