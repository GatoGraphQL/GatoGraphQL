<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootUpdateTagMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMutations\RelationalTypeDataLoaders\UnionType\RootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootUpdateGenericTagTermMutationErrorPayloadUnionTypeResolver extends AbstractRootUpdateTagMutationErrorPayloadUnionTypeResolver
{
    private ?RootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader(RootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader $rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader(): RootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader */
            $rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootUpdateGenericTagTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootUpdateGenericTagTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
