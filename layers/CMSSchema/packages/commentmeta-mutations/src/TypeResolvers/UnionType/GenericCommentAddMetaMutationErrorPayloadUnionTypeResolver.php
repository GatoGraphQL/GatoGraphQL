<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentAddMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCommentAddMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentAddMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader $genericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader = $genericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCommentAddMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCommentAddMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
