<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeDataResolvers;

interface UserAvatarTypeDataResolverInterface
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
    public function getUserAvatars(string | int | object $userObjectOrID): array;
    /**
     * @return string[]
     */
    public function getUserCapabilities(string | int | object $userObjectOrID): array;
    /**
     * @return string|null `null` if the user is not found, its first role otherwise
     */
    public function getTheUserAvatar(string | int | object $userObjectOrID): ?string;
    public function userCan(string | int | object $userObjectOrID, string $capability): bool;
    public function hasRole(string | int | object $userObjectOrID, string $role): bool;
}
