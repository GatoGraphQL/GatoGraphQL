<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers\ObjectType;

use PoPSchema\UserState\FieldResolvers\ObjectType\UserStateFieldResolverTrait;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;

abstract class AbstractUserStateFieldResolver extends AbstractObjectTypeFieldResolver
{
    use UserStateFieldResolverTrait;
}
