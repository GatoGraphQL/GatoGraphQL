<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\UnionType\AbstractErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractErrorPayloadUnionTypeResolver
{
    private ?RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader */
        return $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentCreateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
