<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdatePostMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdatePostMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePostMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
