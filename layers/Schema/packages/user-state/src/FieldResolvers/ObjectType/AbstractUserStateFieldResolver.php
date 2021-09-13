<?php

declare(strict_types=1);

namespace PoPSchema\UserState\FieldResolvers;

use PoPSchema\UserState\FieldResolvers\UserStateFieldResolverTrait;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractDBDataFieldResolver;

abstract class AbstractUserStateFieldResolver extends AbstractDBDataFieldResolver
{
    use UserStateFieldResolverTrait;
}
