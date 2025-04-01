<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver $genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver = $genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver();
    }
}
