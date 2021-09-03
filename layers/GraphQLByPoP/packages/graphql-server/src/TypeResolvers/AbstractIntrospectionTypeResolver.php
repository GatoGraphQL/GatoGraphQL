<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;

abstract class AbstractIntrospectionTypeResolver extends AbstractRelationalTypeResolver
{
    use ReservedNameTypeResolverTrait;
}
