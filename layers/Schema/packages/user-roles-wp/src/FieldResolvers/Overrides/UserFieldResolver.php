<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\FieldResolvers\Overrides;

use PoPSchema\UserRolesWP\FieldResolvers\Overrides\RolesFieldResolverTrait;

class UserFieldResolver extends \PoPSchema\UserRoles\FieldResolvers\UserFieldResolver
{
    use RolesFieldResolverTrait;
}
