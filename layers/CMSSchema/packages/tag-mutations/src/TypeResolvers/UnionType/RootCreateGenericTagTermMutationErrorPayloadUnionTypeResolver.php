<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMutations\TypeResolvers\UnionType\AbstractRootCreateTagMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\TagMutations\RelationalTypeDataLoaders\UnionType\RootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootCreateGenericTagTermMutationErrorPayloadUnionTypeResolver extends AbstractRootCreateTagMutationErrorPayloadUnionTypeResolver
{
    private ?RootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = null;

    final public function setRootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader(RootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader $rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader): void
    {
        $this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
    }
    final protected function getRootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader(): RootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader */
            $rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader = $rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootCreateGenericTagTermMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when creating a tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootCreateGenericTagTermMutationErrorPayloadUnionTypeDataLoader();
    }
}
