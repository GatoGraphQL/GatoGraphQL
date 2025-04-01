<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getRootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver(): RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver */
            $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver = $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver();
    }
}
