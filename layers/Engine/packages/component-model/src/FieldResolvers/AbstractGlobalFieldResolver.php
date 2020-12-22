<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\GlobalFieldResolverTrait;

abstract class AbstractGlobalFieldResolver extends AbstractDBDataFieldResolver
{
    use GlobalFieldResolverTrait;
}
