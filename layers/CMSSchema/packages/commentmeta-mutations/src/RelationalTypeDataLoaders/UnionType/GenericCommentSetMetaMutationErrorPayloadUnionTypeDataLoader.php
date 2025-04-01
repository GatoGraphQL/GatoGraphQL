<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver $genericCommentSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentSetMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentSetMetaMutationErrorPayloadUnionTypeResolver = $genericCommentSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCommentSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
