<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CommentUpdateMutationErrorPayloadUnionTypeResolver $commentUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentUpdateMutationErrorPayloadUnionTypeResolver(): CommentUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentUpdateMutationErrorPayloadUnionTypeResolver */
            $commentUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->commentUpdateMutationErrorPayloadUnionTypeResolver = $commentUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentUpdateMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCommentUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
