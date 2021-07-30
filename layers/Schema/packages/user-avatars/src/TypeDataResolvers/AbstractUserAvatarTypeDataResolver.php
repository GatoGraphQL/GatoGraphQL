<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeDataResolvers;

use PoP\Hooks\HooksAPIInterface;

abstract class AbstractUserAvatarTypeDataResolver implements UserAvatarTypeDataResolverInterface
{
    public function __construct(
        protected HooksAPIInterface $hooksAPI
    ) {
    }

    public function getTheUserAvatar(string | int | object $userObjectOrID): ?string
    {
        $roles = $this->getUserAvatars($userObjectOrID);
        $role = $roles[0] ?? null;
        // Allow URE to override this function
        return $this->hooksAPI->applyFilters(
            'getTheUserAvatar',
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
        $roles = $this->getUserAvatars($userObjectOrID);
        return in_array($role, $roles);
    }
}
