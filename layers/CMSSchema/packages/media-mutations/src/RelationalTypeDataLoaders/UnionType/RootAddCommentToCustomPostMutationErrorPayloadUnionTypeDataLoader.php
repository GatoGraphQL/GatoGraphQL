<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MediaMutations\TypeResolvers\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = null;

    final public function setRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver): void
    {
        $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver(): RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver
    {
        if ($this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver === null) {
            /** @var RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver */
            $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver::class);
            $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
        }
        return $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
