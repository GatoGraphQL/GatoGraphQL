<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMetaMutations\TypeResolvers\UnionType;

use PoPCMSSchema\TagMetaMutations\TypeResolvers\UnionType\AbstractRootAddTagTermMetaMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\CustomPostTagMetaMutations\RelationalTypeDataLoaders\UnionType\RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeResolver extends AbstractRootAddTagTermMetaMutationErrorPayloadUnionTypeResolver
{
    private ?RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader(): RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader */
            $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader = $rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootAddGenericTagTermMetaMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when adding meta on a tag term', 'post-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootAddGenericTagTermMetaMutationErrorPayloadUnionTypeDataLoader();
    }
}
