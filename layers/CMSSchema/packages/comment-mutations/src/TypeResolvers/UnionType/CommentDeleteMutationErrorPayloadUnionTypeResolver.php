<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentDeleteMutationErrorPayloadUnionTypeResolver extends AbstractDeleteCommentMutationErrorPayloadUnionTypeResolver
{
    private ?CommentDeleteMutationErrorPayloadUnionTypeDataLoader $commentDeleteMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCommentDeleteMutationErrorPayloadUnionTypeDataLoader(): CommentDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->commentDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CommentDeleteMutationErrorPayloadUnionTypeDataLoader */
            $commentDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CommentDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->commentDeleteMutationErrorPayloadUnionTypeDataLoader = $commentDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->commentDeleteMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentDeleteMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a comment (nested mutations)', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}
