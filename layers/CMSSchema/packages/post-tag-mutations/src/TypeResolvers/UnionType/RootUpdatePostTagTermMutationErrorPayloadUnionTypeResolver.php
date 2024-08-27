<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootUpdateTagTermMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdatePostTagTermMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateTagTermMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader $rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader(RootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader $rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader(): RootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdatePostTagTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a post tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePostTagTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
