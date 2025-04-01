<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootAddCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\RootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddGenericCommentMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootAddCommentMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader(): RootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $rootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddGenericCommentMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a comment', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
