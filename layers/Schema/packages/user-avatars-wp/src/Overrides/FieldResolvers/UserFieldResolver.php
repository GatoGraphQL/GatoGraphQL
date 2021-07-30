<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatarsWP\Overrides\FieldResolvers;

class UserFieldResolver extends \PoPSchema\UserAvatars\FieldResolvers\UserFieldResolver
{
    use RolesFieldResolverTrait;
}
