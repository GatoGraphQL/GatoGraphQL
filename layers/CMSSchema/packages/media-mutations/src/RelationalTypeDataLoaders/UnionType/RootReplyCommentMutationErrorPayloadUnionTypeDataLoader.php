<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootReplyCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootReplyCommentMutationErrorPayloadUnionTypeResolver $rootReplyCommentMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootReplyCommentMutationErrorPayloadUnionTypeResolver(RootReplyCommentMutationErrorPayloadUnionTypeResolver $rootReplyCommentMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootReplyCommentMutationErrorPayloadUnionTypeResolver(): RootReplyCommentMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootReplyCommentMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootReplyCommentMutationErrorPayloadUnionTypeResolver */
            $rootReplyCommentMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeResolver::class);
            $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootReplyCommentMutationErrorPayloadUnionTypeResolver();
    }
}
