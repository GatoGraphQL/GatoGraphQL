<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\MediaMutations\RelationalTypeDataLoaders\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostAddCommentMutationErrorPayloadUnionTypeResolver extends AbstractCommentMutationErrorPayloadUnionTypeResolver
{
    private ?CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader(CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader(): CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader */
            $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader::class);
            $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPostAddCommentMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment to a custom post (using nested mutations)', 'media-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
