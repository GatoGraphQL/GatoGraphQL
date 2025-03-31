<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostMetaMutations\TypeResolvers\UnionType\AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMetaMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdatePostTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateCustomPostMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdatePostTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating meta on a post\'s custom post', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePostTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
