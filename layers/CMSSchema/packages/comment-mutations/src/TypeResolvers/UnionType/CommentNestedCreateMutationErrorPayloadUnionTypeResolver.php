<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentNestedCreateMutationErrorPayloadUnionTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\UnionType\AbstractErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentNestedCreateMutationErrorPayloadUnionTypeResolver extends AbstractErrorPayloadUnionTypeResolver
{
    private ?CommentNestedCreateMutationErrorPayloadUnionTypeDataLoader $commentNestedCreateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setCommentNestedCreateMutationErrorPayloadUnionTypeDataLoader(CommentNestedCreateMutationErrorPayloadUnionTypeDataLoader $commentNestedCreateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->commentNestedCreateMutationErrorPayloadUnionTypeDataLoader = $commentNestedCreateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCommentNestedCreateMutationErrorPayloadUnionTypeDataLoader(): CommentNestedCreateMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CommentNestedCreateMutationErrorPayloadUnionTypeDataLoader */
        return $this->commentNestedCreateMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(CommentNestedCreateMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CommentNestedCreateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentNestedCreateMutationErrorPayloadUnionTypeDataLoader();
    }
}
