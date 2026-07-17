<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CommentUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentUpdateMutationErrorPayloadUnionTypeResolver extends AbstractUpdateCommentMutationErrorPayloadUnionTypeResolver
{
    private ?CommentUpdateMutationErrorPayloadUnionTypeDataLoader $commentUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCommentUpdateMutationErrorPayloadUnionTypeDataLoader(): CommentUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->commentUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CommentUpdateMutationErrorPayloadUnionTypeDataLoader */
            $commentUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CommentUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->commentUpdateMutationErrorPayloadUnionTypeDataLoader = $commentUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->commentUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a comment (nested mutations)', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
