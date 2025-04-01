<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCommentMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteGenericCommentMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a comment', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
