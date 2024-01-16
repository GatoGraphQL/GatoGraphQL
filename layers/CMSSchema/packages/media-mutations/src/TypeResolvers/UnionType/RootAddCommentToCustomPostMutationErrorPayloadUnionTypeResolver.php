<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType\RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddCommentToCustomPostMutationErrorPayloadUnionTypeResolver extends AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader(): RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader */
            $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader = $rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddCommentToCustomPostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment to a custom post', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddCommentToCustomPostMutationErrorPayloadUnionTypeDataLoader();
    }
}
