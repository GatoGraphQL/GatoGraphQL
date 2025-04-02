<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\CommentAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CommentAddMetaMutationErrorPayloadUnionTypeResolver $commentAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentAddMetaMutationErrorPayloadUnionTypeResolver(): CommentAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentAddMetaMutationErrorPayloadUnionTypeResolver */
            $commentAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->commentAddMetaMutationErrorPayloadUnionTypeResolver = $commentAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCommentAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
