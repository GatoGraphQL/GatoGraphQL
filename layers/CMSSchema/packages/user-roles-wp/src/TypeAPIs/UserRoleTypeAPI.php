<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\TypeAPIs;

use PoPCMSSchema\UserRoles\TypeAPIs\AbstractUserRoleTypeAPI;
use WP_User;

use function get_user_by;
use function wp_roles;
use function get_role;
use function user_can;

class UserRoleTypeAPI extends AbstractUserRoleTypeAPI
{
    public function getAdminRoleName(): string
    {
        return 'administrator';
    }

    /**
     * @return string[]
     */
    public function getRoleNames(): array
    {
        $userRoles = wp_roles();
        return array_keys($userRoles->roles);
    }

    /**
     * All available capabilities
     *
     * @return string[]
     */
    public function getCapabilities(): array
    {
        /**
         * Merge all capabilities from all roles
         */
        $capabilities = [];
        $roles = wp_roles();
        foreach ($roles->roles as $role) {
            $capabilities = array_merge(
                $capabilities,
                array_keys($role['capabilities'])
            );
        }
        /** @var string[] */
        $capabilities = array_values(array_unique($capabilities));
        sort($capabilities);
        return $capabilities;
    }

    /**
     * @return string[]
     */
    public function getUserRoles(string|int|object $userObjectOrID): array
    {
        if (is_object($userObjectOrID)) {
            $user = $userObjectOrID;
        } else {
            $user = get_user_by('id', $userObjectOrID);
            if ($user === false) {
                return [];
            }
        }
        /** @var WP_User $user */
        return $user->roles;
    }

    /**
     * @return string[]
     */
    public function getUserCapabilities(string|int|object $userObjectOrID): array
    {
        $roles = $this->getUserRoles($userObjectOrID);
        $capabilities = [];
        foreach ($roles as $roleName) {
            $role = get_role($roleName);
            $capabilities = array_merge(
                $capabilities,
                array_keys($role->capabilities ?? [])
            );
        }
        $capabilities = array_values(array_unique($capabilities));
        sort($capabilities);
        return $capabilities;
    }

    /**
     * @return string|null `null` if the user is not found, its first role otherwise
     */
    public function getTheUserRole(string|int|object $userObjectOrID): ?string
    {
        $roles = $this->getUserRoles($userObjectOrID);
        return $roles[0] ?? null;
    }

    public function userCan(string|int|object $userObjectOrID, string $capability, mixed ...$args): bool
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
            $userID = $user->ID;
        } else {
            $userID = (int) $userObjectOrID;
        }
        return user_can($userID, $capability, ...$args);
    }
}
