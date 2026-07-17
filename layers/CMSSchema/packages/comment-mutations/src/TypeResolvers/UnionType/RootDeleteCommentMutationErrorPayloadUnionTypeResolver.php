<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootDeleteCommentMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteCommentMutationErrorPayloadUnionTypeResolver extends AbstractDeleteCommentMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteCommentMutationErrorPayloadUnionTypeDataLoader $rootDeleteCommentMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteCommentMutationErrorPayloadUnionTypeDataLoader(): RootDeleteCommentMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteCommentMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteCommentMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteCommentMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteCommentMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteCommentMutationErrorPayloadUnionTypeDataLoader = $rootDeleteCommentMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteCommentMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteCommentMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a comment', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
