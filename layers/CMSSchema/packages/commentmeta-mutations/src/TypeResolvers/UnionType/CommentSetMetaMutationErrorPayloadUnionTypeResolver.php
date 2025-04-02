<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\CommentSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?CommentSetMetaMutationErrorPayloadUnionTypeDataLoader $commentSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCommentSetMetaMutationErrorPayloadUnionTypeDataLoader(): CommentSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->commentSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CommentSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $commentSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CommentSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->commentSetMetaMutationErrorPayloadUnionTypeDataLoader = $commentSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->commentSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
