<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\CommentAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class CommentAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?CommentAddMetaMutationErrorPayloadUnionTypeDataLoader $commentAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getCommentAddMetaMutationErrorPayloadUnionTypeDataLoader(): CommentAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->commentAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var CommentAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $commentAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(CommentAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->commentAddMetaMutationErrorPayloadUnionTypeDataLoader = $commentAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->commentAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CommentAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getCommentAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
