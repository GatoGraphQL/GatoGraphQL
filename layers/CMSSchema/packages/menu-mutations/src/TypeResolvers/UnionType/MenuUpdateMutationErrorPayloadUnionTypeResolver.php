<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeResolvers\UnionType;

use PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\UnionType\MenuUpdateMutationErrorPayloadUnionTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class MenuUpdateMutationErrorPayloadUnionTypeResolver extends AbstractUpdateMenuMutationErrorPayloadUnionTypeResolver
{
    private ?MenuUpdateMutationErrorPayloadUnionTypeDataLoader $menuUpdateMutationErrorPayloadUnionTypeDataLoader = null;

    final protected function getMenuUpdateMutationErrorPayloadUnionTypeDataLoader(): MenuUpdateMutationErrorPayloadUnionTypeDataLoader
    {
        if ($this->menuUpdateMutationErrorPayloadUnionTypeDataLoader === null) {
            /** @var MenuUpdateMutationErrorPayloadUnionTypeDataLoader */
            $menuUpdateMutationErrorPayloadUnionTypeDataLoader = $this->instanceManager->getInstance(MenuUpdateMutationErrorPayloadUnionTypeDataLoader::class);
            $this->menuUpdateMutationErrorPayloadUnionTypeDataLoader = $menuUpdateMutationErrorPayloadUnionTypeDataLoader;
        }
        return $this->menuUpdateMutationErrorPayloadUnionTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'MenuUpdateMutationErrorPayloadUnion';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Union of \'Error Payload\' types when updating a menu (nested mutations)', 'menu-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getMenuUpdateMutationErrorPayloadUnionTypeDataLoader();
    }
}
