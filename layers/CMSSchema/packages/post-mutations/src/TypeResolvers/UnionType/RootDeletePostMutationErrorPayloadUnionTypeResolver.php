<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootDeleteCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\RootDeletePostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeletePostMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeletePostMutationErrorPayloadUnionTypeDataLoader $rootDeletePostMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeletePostMutationErrorPayloadUnionTypeDataLoader(): RootDeletePostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeletePostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeletePostMutationErrorPayloadUnionTypeDataLoader */
            $rootDeletePostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeletePostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeletePostMutationErrorPayloadUnionTypeDataLoader = $rootDeletePostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeletePostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeletePostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a post', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeletePostMutationErrorPayloadUnionTypeDataLoader();
    }
}
