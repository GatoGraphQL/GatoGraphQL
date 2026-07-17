<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\UnionType;

use PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\UnionType\RootDeleteMenuMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class RootDeleteMenuMutationErrorPayloadUnionTypeResolver extends AbstractDeleteMenuMutationErrorPayloadUnionTypeResolver
{
    private ?RootDeleteMenuMutationErrorPayloadUnionTypeDataLoader $rootDeleteMenuMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getRootDeleteMenuMutationErrorPayloadUnionTypeDataLoader(): RootDeleteMenuMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->rootDeleteMenuMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var RootDeleteMenuMutationErrorPayloadUnionTypeDataLoader */
            $rootDeleteMenuMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(RootDeleteMenuMutationErrorPayloadUnionTypeDataLoader::class);
            $this->rootDeleteMenuMutationErrorPayloadUnionTypeDataLoader = $rootDeleteMenuMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->rootDeleteMenuMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'RootDeleteMenuMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when deleting a menu', 'gatographql');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getRootDeleteMenuMutationErrorPayloadUnionTypeDataLoader();
    }
}
