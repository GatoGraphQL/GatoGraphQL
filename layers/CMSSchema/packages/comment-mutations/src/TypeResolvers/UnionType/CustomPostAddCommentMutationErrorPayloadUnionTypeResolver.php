<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMutations\RelationalTypeDataLoaders\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\UnionType\AbstractErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CustomPostAddCommentMutationErrorPayloadUnionTypeResolver extends AbstractErrorPayloadUnionTypeResolver
{
    private ?CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader(CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader = $customPostAddCommentMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader(): CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader */
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostAddCommentMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding a comment to a custom post (using nested mutations)', 'comment-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeDataLoader();
    }
}
