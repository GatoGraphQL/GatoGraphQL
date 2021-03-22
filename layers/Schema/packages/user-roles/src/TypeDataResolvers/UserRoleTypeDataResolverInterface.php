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
     * @return string[]
     */
    public function getRoleNames(): array;
    /**
     * All available capabilities
     *
     * @return string[]
     */
    public function getCapabilities(): array;
    /**
     * @return string[]
     */
    public function getUserRoles(mixed $userObjectOrID): array;
    /**
     * @return string[]
     */
    public function getUserCapabilities(mixed $userObjectOrID): array;
    public function getTheUserRole(mixed $userObjectOrID): string;
    public function userCan(mixed $userObjectOrID, string $capability): bool;
    public function hasRole(mixed $userObjectOrID, string $role): bool;
}
