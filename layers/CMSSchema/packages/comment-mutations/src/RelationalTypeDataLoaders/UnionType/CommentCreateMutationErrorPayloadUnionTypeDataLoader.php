<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CommentCreateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver(): RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
