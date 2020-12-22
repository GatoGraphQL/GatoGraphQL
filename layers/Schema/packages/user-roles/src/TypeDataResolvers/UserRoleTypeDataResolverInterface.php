<?php

declare(strict_types=1);

namespace PoPSchema\UserRoles\TypeDataResolvers;

interface UserRoleTypeDataResolverInterface
{
    /**
     * Admin role name
     *
     * @return string
     */
    public function getAdminRoleName(): string;
    /**
     * Role names
     *
     * @return array
     */
    public function getRoleNames(): array;
    /**
     * All available capabilities
     *
     * @return array
     */
    public function getCapabilities(): array;
    /**
     * User roles
     *
     * @param [type] $userObjectOrID
     * @return array
     */
    public function getUserRoles($userObjectOrID): array;
    /**
     * User capabilities
     *
     * @param [type] $userObjectOrID
     * @return array
     */
    public function getUserCapabilities($userObjectOrID): array;
    public function getTheUserRole($userObjectOrID);
    public function userCan($userObjectOrID, $capability);
    public function hasRole($userObjectOrID, $role);
}
