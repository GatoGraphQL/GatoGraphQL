<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\CustomPostCreateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostCreateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostCreateMutationErrorPayloadUnionTypeResolver $customPostCreateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostCreateMutationErrorPayloadUnionTypeResolver(CustomPostCreateMutationErrorPayloadUnionTypeResolver $customPostCreateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostCreateMutationErrorPayloadUnionTypeResolver = $customPostCreateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostCreateMutationErrorPayloadUnionTypeResolver(): CustomPostCreateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostCreateMutationErrorPayloadUnionTypeResolver */
        return $this->customPostCreateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostCreateMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCustomPostCreateMutationErrorPayloadUnionTypeResolver();
    }
}
