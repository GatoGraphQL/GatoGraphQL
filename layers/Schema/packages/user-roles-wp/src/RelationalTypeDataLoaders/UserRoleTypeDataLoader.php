<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\RelationalTypeDataLoaders;

use PoP\ComponentModel\RelationalTypeDataLoaders\AbstractRelationalTypeDataLoader;

class UserRoleTypeDataLoader extends AbstractRelationalTypeDataLoader
{
    public function getObjects(array $ids): array
    {
        return array_map('\get_role', $ids);
    }
}
