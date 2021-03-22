<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\TypeDataResolvers;

interface UserRoleTypeDataResolverInterface
{
    /**
     * Admin role name
     */
    public function getAdminRoleName(): string;
    /**
     * Role names
     */
    public function getRoleNames(): array;
    /**
     * All available capabilities
     */
    public function getCapabilities(): array;
    /**
     * User roles
     *
     * @param [type] $userObjectOrID
     */
    public function getUserRoles($userObjectOrID): array;
    /**
     * User capabilities
     *
     * @param [type] $userObjectOrID
     */
    public function getUserCapabilities($userObjectOrID): array;
    public function getTheUserRole($userObjectOrID);
    public function userCan($userObjectOrID, $capability);
    public function hasRole($userObjectOrID, $role);
}
