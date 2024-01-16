<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostAddCommentMutationErrorPayloadUnionTypeResolver(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver(): CustomPostAddCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->customPostAddCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeResolver */
            $customPostAddCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver();
    }
}
