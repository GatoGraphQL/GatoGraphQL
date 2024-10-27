<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver = null;

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
