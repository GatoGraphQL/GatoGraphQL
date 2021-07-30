<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatarsWP\Overrides\FieldResolvers;

class RootRolesFieldResolver extends \PoPSchema\UserAvatars\FieldResolvers\RootRolesFieldResolver
{
    use RolesFieldResolverTrait;
}
