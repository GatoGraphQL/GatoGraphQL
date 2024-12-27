<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRoles\TypeAPIs;

use PoP\Root\Services\AbstractBasicService;

abstract class AbstractUserRoleTypeAPI extends AbstractBasicService implements UserRoleTypeAPIInterface
{
    public function getTheUserRole(string|int|object $userObjectOrID): ?string
    {
        $roles = $this->getUserRoles($userObjectOrID);
        $role = $roles[0] ?? null;
        // Allow URE to override this function
        // @todo convert the hook from string to const, then re-enable
        // return App::applyFilters(
        //     'getTheUserRole',
        //     $role,
        //     $userObjectOrID
        // );
        return $role;
    }

    public function userCan(string|int|object $userObjectOrID, string $capability, mixed ...$args): bool
    {
        $capabilities = $this->getUserCapabilities($userObjectOrID);
        return in_array($capability, $capabilities);
    }

    public function hasRole(string|int|object $userObjectOrID, string $role): bool
    {
        $roles = $this->getUserRoles($userObjectOrID);
        return in_array($role, $roles);
    }
}
