<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\UsersWP\TypeAPIs\UserTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            UserTypeAPI::HOOK_QUERY,
            $this->convertUsersQuery(...),
            10,
            2
        );
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
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
