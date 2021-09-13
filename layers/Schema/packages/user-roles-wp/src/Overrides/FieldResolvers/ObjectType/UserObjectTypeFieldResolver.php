<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

class UserObjectTypeFieldResolver extends \PoPSchema\UserRoles\FieldResolvers\ObjectType\UserObjectTypeFieldResolver
{
    use RolesObjectTypeFieldResolverTrait;
}
