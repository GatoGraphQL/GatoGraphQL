<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootSetCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\RootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootSetGenericCommentMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootSetCommentMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader(): RootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $rootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootSetGenericCommentMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a comment', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootSetGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
