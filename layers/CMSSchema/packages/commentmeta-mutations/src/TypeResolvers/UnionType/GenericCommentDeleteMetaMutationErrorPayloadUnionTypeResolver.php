<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\AbstractCommentDeleteMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CommentMetaMutations\RelationalTypeDataLoaders\UnionType\GenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class GenericCommentDeleteMetaMutationErrorPayloadUnionTypeResolver extends AbstractCommentDeleteMetaMutationErrorPayloadUnionTypeResolver
{
    private ?GenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader $genericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getGenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader(): GenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var GenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader */
            $genericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(GenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader = $genericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->genericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'GenericCommentDeleteMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a comment (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getGenericCommentDeleteMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
