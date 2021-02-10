<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers;

class RootRolesFieldResolver extends \PoPSchema\UserRoles\FieldResolvers\RootRolesFieldResolver
{
    use RolesFieldResolverTrait;
}
