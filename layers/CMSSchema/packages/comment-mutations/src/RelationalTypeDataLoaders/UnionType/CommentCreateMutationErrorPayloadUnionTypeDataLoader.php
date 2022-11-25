<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentCreateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentCreateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CommentCreateMutationErrorPayloadUnionTypeResolver $commentCreateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCommentCreateMutationErrorPayloadUnionTypeResolver(CommentCreateMutationErrorPayloadUnionTypeResolver $commentCreateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->commentCreateMutationErrorPayloadUnionTypeResolver = $commentCreateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCommentCreateMutationErrorPayloadUnionTypeResolver(): CommentCreateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CommentCreateMutationErrorPayloadUnionTypeResolver */
        return $this->commentCreateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CommentCreateMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCommentCreateMutationErrorPayloadUnionTypeResolver();
    }
}
