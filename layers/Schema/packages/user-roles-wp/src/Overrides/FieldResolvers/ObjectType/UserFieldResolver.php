<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers\ObjectType;

class UserFieldResolver extends \PoPSchema\UserRoles\FieldResolvers\ObjectType\UserFieldResolver
{
    use RolesFieldResolverTrait;
}
