<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

abstract class AbstractIntrospectionTypeResolver extends AbstractObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;
}
