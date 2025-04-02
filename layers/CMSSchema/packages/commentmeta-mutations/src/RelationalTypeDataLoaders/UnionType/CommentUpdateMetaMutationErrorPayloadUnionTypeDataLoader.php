<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\CommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CommentUpdateMetaMutationErrorPayloadUnionTypeResolver $commentUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentUpdateMetaMutationErrorPayloadUnionTypeResolver(): CommentUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $commentUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->commentUpdateMetaMutationErrorPayloadUnionTypeResolver = $commentUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCommentUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
