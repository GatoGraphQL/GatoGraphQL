<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

use PoPSchema\UserRoles\FieldResolvers\ObjectType\RootRolesObjectTypeFieldResolver as UpstreamRootRolesObjectTypeFieldResolver;

class RootRolesObjectTypeFieldResolver extends UpstreamRootRolesObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;
}
