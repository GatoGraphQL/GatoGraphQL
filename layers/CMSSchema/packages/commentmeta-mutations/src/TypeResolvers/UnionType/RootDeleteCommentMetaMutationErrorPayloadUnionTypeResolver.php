<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader $rootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteCommentMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a comment', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteCommentMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
