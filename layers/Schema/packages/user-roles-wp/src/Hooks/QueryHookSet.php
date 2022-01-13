<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\UsersWP\TypeAPIs\UserTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            UserTypeAPI::HOOK_QUERY,
            [$this, 'convertUsersQuery'],
            10,
            2
        );
    }

    public function convertUsersQuery(array $query, array $options): array
    {
        if (isset($query['user-roles'])) {
            $query['role__in'] = $query['user-roles'];
            unset($query['user-roles']);
        }
        if (isset($query['exclude-user-roles'])) {
            $query['role__not_in'] = $query['exclude-user-roles'];
            unset($query['exclude-user-roles']);
        }
        return $query;
    }
}
