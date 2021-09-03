<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\RelationalTypeDataLoaders\Object;

use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeDataLoader;

class UserRoleTypeDataLoader extends AbstractObjectTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return array_map('\get_role', $ids);
    }
}
