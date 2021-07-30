<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesWP\Overrides\FieldResolvers;

class RootRolesFieldResolver extends \PoPSchema\UserAvatars\FieldResolvers\RootRolesFieldResolver
{
    use RolesFieldResolverTrait;
}
