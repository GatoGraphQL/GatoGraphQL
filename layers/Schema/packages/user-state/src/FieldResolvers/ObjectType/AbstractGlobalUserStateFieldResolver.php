<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\GlobalFieldResolverTrait;
use PoPSchema\UserState\FieldResolvers\ObjectType\AbstractUserStateFieldResolver;

abstract class AbstractGlobalUserStateFieldResolver extends AbstractUserStateFieldResolver
{
    use GlobalFieldResolverTrait;
}
