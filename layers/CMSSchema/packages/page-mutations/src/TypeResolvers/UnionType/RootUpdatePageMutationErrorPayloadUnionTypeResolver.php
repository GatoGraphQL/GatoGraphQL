<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\TypeResolvers\UnionType;

use PoPCMSSchema\PageMutations\RelationalTypeDataLoaders\UnionType\RootUpdatePageMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdatePageMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdatePageMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdatePageMutationErrorPayloadUnionTypeDataLoader $rootUpdatePageMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootUpdatePageMutationErrorPayloadUnionTypeDataLoader(): RootUpdatePageMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdatePageMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdatePageMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdatePageMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdatePageMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdatePageMutationErrorPayloadUnionTypeDataLoader = $rootUpdatePageMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdatePageMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdatePageMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a page', 'page-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdatePageMutationErrorPayloadUnionTypeDataLoader();
    }
}
