<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\TypeAPIs;

use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Hooks\HooksAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractUserRoleTypeAPI implements UserRoleTypeAPIInterface
{
    use WithHooksAPIServiceTrait;

    public function getTheUserRole(string | int | object $userObjectOrID): ?string
    {
        $roles = $this->getUserRoles($userObjectOrID);
        $role = $roles[0] ?? null;
        // Allow URE to override this function
        return $this->hooksAPI->applyFilters(
            'getTheUserRole',
            $role,
            $userObjectOrID
        );
    }

    public function userCan(string | int | object $userObjectOrID, string $capability): bool
    {
        $capabilities = $this->getUserCapabilities($userObjectOrID);
        return in_array($capability, $capabilities);
    }

    public function hasRole(string | int | object $userObjectOrID, string $role): bool
    {
        $roles = $this->getUserRoles($userObjectOrID);
        return in_array($role, $roles);
    }
}
