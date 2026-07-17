<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\RootUpdateCommentMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateCommentMutationErrorPayloadUnionTypeResolver extends AbstractUpdateCommentMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateCommentMutationErrorPayloadUnionTypeDataLoader $rootUpdateCommentMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateCommentMutationErrorPayloadUnionTypeDataLoader(): RootUpdateCommentMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateCommentMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateCommentMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateCommentMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateCommentMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateCommentMutationErrorPayloadUnionTypeDataLoader = $rootUpdateCommentMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateCommentMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateCommentMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a comment', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
