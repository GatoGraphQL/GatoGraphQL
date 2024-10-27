<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractTagDeleteMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\PostTagDeleteMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostTagDeleteMutationErrorPayloadUnionTypeResolver extends AbstractTagDeleteMutationErrorPayloadUnionTypeResolver
{
    private ?PostTagDeleteMutationErrorPayloadUnionTypeDataLoader $postTagDeleteMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getPostTagDeleteMutationErrorPayloadUnionTypeDataLoader(): PostTagDeleteMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->postTagDeleteMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var PostTagDeleteMutationErrorPayloadUnionTypeDataLoader */
            $postTagDeleteMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(PostTagDeleteMutationErrorPayloadUnionTypeDataLoader::class);
            $this->postTagDeleteMutationErrorPayloadUnionTypeDataLoader = $postTagDeleteMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->postTagDeleteMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'PostTagDeleteMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a post tag term (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostTagDeleteMutationErrorPayloadUnionTypeDataLoader();
    }
}
