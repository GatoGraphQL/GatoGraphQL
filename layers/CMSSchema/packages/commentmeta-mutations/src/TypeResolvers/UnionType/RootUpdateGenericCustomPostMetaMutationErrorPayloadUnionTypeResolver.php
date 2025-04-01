<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateGenericCommentMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a comment', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericCommentMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
