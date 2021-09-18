<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoPSchema\UserRoles\FieldResolvers\ObjectType\UserObjectTypeFieldResolver as UpstreamUserObjectTypeFieldResolver;

class UserObjectTypeFieldResolver extends UpstreamUserObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;
}
