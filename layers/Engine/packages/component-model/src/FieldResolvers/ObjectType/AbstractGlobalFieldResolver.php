<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\GlobalFieldResolverTrait;

abstract class AbstractGlobalFieldResolver extends AbstractDBDataFieldResolver
{
    use GlobalFieldResolverTrait;
}
