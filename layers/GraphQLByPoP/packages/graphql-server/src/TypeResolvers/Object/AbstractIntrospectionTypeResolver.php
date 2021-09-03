<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\Object;

use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\Object\AbstractObjectTypeResolver;

abstract class AbstractIntrospectionTypeResolver extends AbstractObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;
}
