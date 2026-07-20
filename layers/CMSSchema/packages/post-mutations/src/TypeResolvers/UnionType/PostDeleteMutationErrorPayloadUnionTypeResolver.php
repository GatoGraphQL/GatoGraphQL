<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\PostDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostDeleteMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostDeleteMutationErrorPayloadUnionTypeResolver
{
    private ?PostDeleteMutationErrorPayloadUnionTypeDataLoader $postDeleteMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostDeleteMutationErrorPayloadUnionTypeDataLoader(): PostDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostDeleteMutationErrorPayloadUnionTypeDataLoader */
            $postDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postDeleteMutationErrorPayloadUnionTypeDataLoader = $postDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postDeleteMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostDeleteMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a post', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}
