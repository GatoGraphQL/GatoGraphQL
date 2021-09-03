<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers;

use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\AbstractObjectTypeResolver;

abstract class AbstractIntrospectionTypeResolver extends AbstractObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;
}
