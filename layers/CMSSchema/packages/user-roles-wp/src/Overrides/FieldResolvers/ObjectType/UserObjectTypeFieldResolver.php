<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoPSchema\UserRoles\FieldResolvers\ObjectType\UserObjectTypeFieldResolver as UpstreamUserObjectTypeFieldResolver;
use PoPSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;

class UserObjectTypeFieldResolver extends UpstreamUserObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;

    private ?UserRoleObjectTypeResolver $userRoleObjectTypeResolver = null;

    final public function setUserRoleObjectTypeResolver(UserRoleObjectTypeResolver $userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        return $this->userRoleObjectTypeResolver ??= $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
    }
}
