<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\TypeDataResolvers;

use PoPSchema\UserRoles\TypeDataResolvers\AbstractUserRoleTypeDataResolver;

class UserRoleTypeDataResolver extends AbstractUserRoleTypeDataResolver
{
    public function getAdminRoleName(): string
    {
        return 'administrator';
    }

    public function getRoleNames(): array
    {
        $userRoles = \wp_roles();
        return array_keys($userRoles->roles);
    }

    public function getCapabilities(): array
    {
        /**
         * Merge all capabilities from all roles
         */
        $capabilities = [];
        $roles = \wp_roles();
        foreach ($roles->roles as $role) {
            $capabilities = array_merge(
                $capabilities,
                array_keys($role['capabilities'])
            );
        }
        return array_values(array_unique($capabilities));
    }

    public function getUserRoles($userObjectOrID): array
    {
        if (is_object($userObjectOrID)) {
            $user = $userObjectOrID;
        } else {
            $user = \get_user_by('id', $userObjectOrID);
        }
        return $user->roles;
    }

    public function getUserCapabilities($userObjectOrID): array
    {
        $roles = $this->getUserRoles($userObjectOrID);
        $capabilities = [];
        foreach ($roles as $roleName) {
            $role = \get_role($roleName);
            $capabilities = array_merge(
                $capabilities,
                array_keys($role->capabilities ?? [])
            );
        }
        return array_values(array_unique($capabilities));
    }

    public function getTheUserRole($userObjectOrID)
    {
        return \get_the_user_role($userObjectOrID);
    }

    public function userCan($userObjectOrID, $capability)
    {
        return \user_can($userObjectOrID, $capability);
    }
}
