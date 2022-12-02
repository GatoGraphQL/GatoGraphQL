<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\PostUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class PostUpdateMutationErrorPayloadUnionTypeResolver extends AbstractCustomPostUpdateMutationErrorPayloadUnionTypeResolver
{
    private ?PostUpdateMutationErrorPayloadUnionTypeDataLoader $postUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setPostUpdateMutationErrorPayloadUnionTypeDataLoader(PostUpdateMutationErrorPayloadUnionTypeDataLoader $postUpdateMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->postUpdateMutationErrorPayloadUnionTypeDataLoader = $postUpdateMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getPostUpdateMutationErrorPayloadUnionTypeDataLoader(): PostUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        /** @var PostUpdateMutationErrorPayloadUnionTypeDataLoader */
        return $this->postUpdateMutationErrorPayloadUnionTypeDataLoader ??= $this->instanceManager->getInstance(PostUpdateMutationErrorPayloadUnionTypeDataLoader::class);
    }

    public function getTypeName(): string
    {
        return 'PostUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a post (using nested mutations)', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getPostUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
