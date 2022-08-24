<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoPCMSSchema\UserRoles\FieldResolvers\ObjectType\RootRolesObjectTypeFieldResolver as UpstreamRootRolesObjectTypeFieldResolver;
use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;

class RootRolesObjectTypeFieldResolver extends UpstreamRootRolesObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;

    private ?UserRoleObjectTypeResolver $userRoleObjectTypeResolver = null;

    final public function setUserRoleObjectTypeResolver(UserRoleObjectTypeResolver $userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        /** @var UserRoleObjectTypeResolver */
        return $this->userRoleObjectTypeResolver ??= $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
    }
}
