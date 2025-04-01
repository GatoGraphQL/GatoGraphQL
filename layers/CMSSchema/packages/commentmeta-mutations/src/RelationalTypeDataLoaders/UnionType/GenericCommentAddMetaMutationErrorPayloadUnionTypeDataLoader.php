<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver $genericCommentAddMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentAddMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentAddMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentAddMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentAddMetaMutationErrorPayloadUnionTypeResolver = $genericCommentAddMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentAddMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCommentAddMetaMutationErrorPayloadUnionTypeResolver();
    }
}
