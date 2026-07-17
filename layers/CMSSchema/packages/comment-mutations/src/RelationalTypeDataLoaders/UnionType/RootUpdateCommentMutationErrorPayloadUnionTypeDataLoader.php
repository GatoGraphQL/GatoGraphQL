<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootUpdateCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateCommentMutationErrorPayloadUnionTypeResolver $rootUpdateCommentMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateCommentMutationErrorPayloadUnionTypeResolver(): RootUpdateCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateCommentMutationErrorPayloadUnionTypeResolver */
            $rootUpdateCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateCommentMutationErrorPayloadUnionTypeResolver = $rootUpdateCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateCommentMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateCommentMutationErrorPayloadUnionTypeResolver();
    }
}
