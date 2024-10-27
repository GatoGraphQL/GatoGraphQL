<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdatePostMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCustomPostMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdatePostMutationErrorPayloadUnionTypeDataLoader $rootUpdatePostMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdatePostMutationErrorPayloadUnionTypeDataLoader(): RootUpdatePostMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdatePostMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdatePostMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdatePostMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdatePostMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdatePostMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePostMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdatePostMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdatePostMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePostMutationErrorPayloadUnionTypeDataLoader();
    }
}
