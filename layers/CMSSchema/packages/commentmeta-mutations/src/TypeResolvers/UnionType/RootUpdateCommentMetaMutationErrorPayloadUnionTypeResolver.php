<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCommentMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateCommentMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a comment', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateCommentMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
