<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\FieldResolvers\Overrides;

use PoPSchema\UserRolesWP\FieldResolvers\Overrides\RolesFieldResolverTrait;

class RootRolesFieldResolver extends \PoPSchema\UserRoles\FieldResolvers\RootRolesFieldResolver
{
    use RolesFieldResolverTrait;
}
