<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootReplyCommentMutationErrorPayloadUnionTypeResolver extends AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    private ?RootReplyCommentMutationErrorPayloadUnionTypeDataLoader $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootReplyCommentMutationErrorPayloadUnionTypeDataLoader(RootReplyCommentMutationErrorPayloadUnionTypeDataLoader $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootReplyCommentMutationErrorPayloadUnionTypeDataLoader(): RootReplyCommentMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootReplyCommentMutationErrorPayloadUnionTypeDataLoader */
            $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader = $rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootReplyCommentMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when replying to a comment', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootReplyCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
