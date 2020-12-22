<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\GlobalFieldResolverTrait;
use PoPSchema\UserState\FieldResolvers\AbstractUserStateFieldResolver;

abstract class AbstractGlobalUserStateFieldResolver extends AbstractUserStateFieldResolver
{
    use GlobalFieldResolverTrait;
}
