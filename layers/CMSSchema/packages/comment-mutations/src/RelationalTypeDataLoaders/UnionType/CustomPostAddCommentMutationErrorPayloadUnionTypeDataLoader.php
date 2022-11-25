<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver = null;

    final public function setCustomPostAddCommentMutationErrorPayloadUnionTypeResolver(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver): void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    }
    final protected function getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver(): CustomPostAddCommentMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeResolver */
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver ??= $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver::class);
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver();
    }
}
