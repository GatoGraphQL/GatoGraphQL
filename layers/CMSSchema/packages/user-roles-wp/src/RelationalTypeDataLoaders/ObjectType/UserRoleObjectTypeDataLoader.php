<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;

use function get_role;

class UserRoleObjectTypeDataLoader extends AbstractObjectTypeDataLoader
{
    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects(array $ids): array
    {
        /** @var string[] $ids */
        return array_map(get_role(...), $ids);
    }
}
