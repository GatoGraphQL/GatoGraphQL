<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\UserRoles\FieldResolvers\ObjectType\RootRolesObjectTypeFieldResolver as UpstreamRootRolesObjectTypeFieldResolver;
use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;

class RootRolesObjectTypeFieldResolver extends UpstreamRootRolesObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;

    private ?UserRoleObjectTypeResolver $userRoleObjectTypeResolver = null;

    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        if ($this->userRoleObjectTypeResolver === null) {
            /** @var UserRoleObjectTypeResolver */
            $userRoleObjectTypeResolver = $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
            $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
        }
        return $this->userRoleObjectTypeResolver;
    }
}
