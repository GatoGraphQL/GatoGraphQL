<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\CommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?CommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader $commentUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): CommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->commentUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $commentUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->commentUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $commentUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->commentUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
