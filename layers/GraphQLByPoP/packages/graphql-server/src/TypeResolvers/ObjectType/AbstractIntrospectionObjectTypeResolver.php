<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\TypeResolvers\ReservedNameTypeResolverTrait;

abstract class AbstractIntrospectionObjectTypeResolver extends AbstractObjectTypeResolver
{
    use ReservedNameTypeResolverTrait;
}
