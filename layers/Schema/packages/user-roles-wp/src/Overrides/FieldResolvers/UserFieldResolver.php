<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers;

class UserFieldResolver extends \PoPSchema\UserRoles\FieldResolvers\UserFieldResolver
{
    use RolesFieldResolverTrait;
}
