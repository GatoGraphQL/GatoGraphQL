<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostUpdateMutationErrorPayloadUnionTypeResolver $customPostUpdateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostUpdateMutationErrorPayloadUnionTypeResolver(CustomPostUpdateMutationErrorPayloadUnionTypeResolver $customPostUpdateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostUpdateMutationErrorPayloadUnionTypeResolver = $customPostUpdateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostUpdateMutationErrorPayloadUnionTypeResolver(): CustomPostUpdateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostUpdateMutationErrorPayloadUnionTypeResolver */
        return $this->customPostUpdateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostUpdateMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCustomPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
