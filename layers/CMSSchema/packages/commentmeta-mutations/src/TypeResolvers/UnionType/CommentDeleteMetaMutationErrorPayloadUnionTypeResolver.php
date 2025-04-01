<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\CommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?CommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader $commentDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): CommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->commentDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $commentDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->commentDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $commentDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->commentDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
