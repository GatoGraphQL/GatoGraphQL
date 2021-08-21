<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\UsersWP\TypeAPIs\UserTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
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
        return $query;
    }
}
