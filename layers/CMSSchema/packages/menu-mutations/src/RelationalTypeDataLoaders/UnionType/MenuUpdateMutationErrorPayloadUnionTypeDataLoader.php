<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\MenuMutations\TypeResolvers\UnionType\MenuUpdateMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\UnionType\AbstractUnionTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class MenuUpdateMutationErrorPayloadUnionTypeDataLoader extends AbstractUnionTypeDataLoader
{
    private ?MenuUpdateMutationErrorPayloadUnionTypeResolver $menuUpdateMutationErrorPayloadUnionTypeResolver = null;

    final protected function getMenuUpdateMutationErrorPayloadUnionTypeResolver(): MenuUpdateMutationErrorPayloadUnionTypeResolver
    {
        if ($this->menuUpdateMutationErrorPayloadUnionTypeResolver === null) {
            /** @var MenuUpdateMutationErrorPayloadUnionTypeResolver */
            $menuUpdateMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(MenuUpdateMutationErrorPayloadUnionTypeResolver::class);
            $this->menuUpdateMutationErrorPayloadUnionTypeResolver = $menuUpdateMutationErrorPayloadUnionTypeResolver;
        }
        return $this->menuUpdateMutationErrorPayloadUnionTypeResolver;
    }

    protected function getUnionTypeResolver(): UnionTypeResolverInterface
    {
        return $this->getMenuUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
