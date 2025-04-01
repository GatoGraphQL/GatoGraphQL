<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class GenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver $genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getGenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver(): GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver */
            $genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver = $genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getGenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver();
    }
}
