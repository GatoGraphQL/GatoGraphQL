<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostTagMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateTagTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdatePostTagTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a post\'s tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePostTagTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
