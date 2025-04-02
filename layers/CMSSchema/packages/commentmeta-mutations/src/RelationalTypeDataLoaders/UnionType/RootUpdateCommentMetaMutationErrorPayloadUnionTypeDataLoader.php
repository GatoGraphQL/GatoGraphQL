<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver $rootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
