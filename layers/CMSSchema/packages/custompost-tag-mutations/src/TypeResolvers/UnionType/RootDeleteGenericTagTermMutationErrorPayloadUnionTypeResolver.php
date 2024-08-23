<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\CustomPostTagMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteGenericTagTermMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader(RootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader(): RootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteGenericTagTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericTagTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
