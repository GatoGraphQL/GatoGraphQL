<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;

abstract class AbstractUserStateObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use UserStateObjectTypeFieldResolverTrait;
}
