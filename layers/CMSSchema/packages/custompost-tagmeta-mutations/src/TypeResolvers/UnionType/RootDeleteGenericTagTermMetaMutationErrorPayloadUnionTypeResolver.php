<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootDeleteTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType\RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootDeleteTagTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteGenericTagTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting meta on a tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
