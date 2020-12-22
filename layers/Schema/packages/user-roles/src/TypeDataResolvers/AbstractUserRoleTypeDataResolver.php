<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\TypeDataResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;

abstract class AbstractUserRoleTypeDataResolver implements UserRoleTypeDataResolverInterface
{
    public function getTheUserRole($userObjectOrID)
    {
        $roles = $this->getUserRoles($userObjectOrID);
        $role = $roles[0];
        // Allow URE to override this function
        return HooksAPIFacade::getInstance()->applyFilters(
            'getTheUserRole',
            $role,
            $userObjectOrID
        );
    }

    public function userCan($userObjectOrID, $capability)
    {
        $capabilities = $this->getUserCapabilities($userObjectOrID);
        return in_array($capability, $capabilities);
    }

    public function hasRole($userObjectOrID, $role)
    {
        $roles = $this->getUserRoles($userObjectOrID);
        return in_array($role, $roles);
    }
}
