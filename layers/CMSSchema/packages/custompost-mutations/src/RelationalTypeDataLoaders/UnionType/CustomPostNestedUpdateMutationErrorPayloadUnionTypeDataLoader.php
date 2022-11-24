<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostNestedUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver $customPostNestedUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver(CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver $customPostNestedUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostNestedUpdateMutationErrorPayloadUnionTypeResolver = $customPostNestedUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver(): CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver */
        return $this->customPostNestedUpdateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCustomPostNestedUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
