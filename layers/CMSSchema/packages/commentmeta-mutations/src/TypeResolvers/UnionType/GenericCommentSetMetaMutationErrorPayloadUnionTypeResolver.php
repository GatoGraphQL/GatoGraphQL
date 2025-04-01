<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCommentSetMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentSetMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader $genericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader = $genericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCommentSetMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when setting meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCommentSetMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
