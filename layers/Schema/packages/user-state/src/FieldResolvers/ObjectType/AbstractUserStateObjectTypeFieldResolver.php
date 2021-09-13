<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers\ObjectType;

use PoPSchema\UserState\FieldResolvers\ObjectType\UserStateObjectTypeFieldResolverTrait;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;

abstract class AbstractUserStateObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use UserStateObjectTypeFieldResolverTrait;
}
