<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentUpdateMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCommentUpdateMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentUpdateMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader $genericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader = $genericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCommentUpdateMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCommentUpdateMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
