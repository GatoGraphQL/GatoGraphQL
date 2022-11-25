<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentNestedCreateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentNestedCreateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CommentNestedCreateMutationErrorPayloadUnionTypeResolver $commentNestedCreateMutationErrorPayloadUnionTypeResolver = null;

    final public function setCommentNestedCreateMutationErrorPayloadUnionTypeResolver(CommentNestedCreateMutationErrorPayloadUnionTypeResolver $commentNestedCreateMutationErrorPayloadUnionTypeResolver): void
    {
        $this->commentNestedCreateMutationErrorPayloadUnionTypeResolver = $commentNestedCreateMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCommentNestedCreateMutationErrorPayloadUnionTypeResolver(): CommentNestedCreateMutationErrorPayloadUnionTypeResolver
    {
        /** @var CommentNestedCreateMutationErrorPayloadUnionTypeResolver */
        return $this->commentNestedCreateMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CommentNestedCreateMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCommentNestedCreateMutationErrorPayloadUnionTypeResolver();
    }
}
