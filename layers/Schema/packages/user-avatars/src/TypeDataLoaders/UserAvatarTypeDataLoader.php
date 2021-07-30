<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeDataLoaders;

use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;

class UserAvatarTypeDataLoader extends AbstractTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return array_map(
            fn (string | int $id) => null/*$this->menuItemRuntimeRegistry->getMenuItem($id)*/,
            $ids
        );
    }
}
