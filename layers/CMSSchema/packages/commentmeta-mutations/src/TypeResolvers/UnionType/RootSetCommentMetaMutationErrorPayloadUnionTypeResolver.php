<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetCommentMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetCommentMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader $rootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetCommentMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a comment', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetCommentMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
