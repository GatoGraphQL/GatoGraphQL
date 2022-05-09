<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\TypeResolvers\IntrospectionTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

abstract class AbstractIntrospectionObjectTypeResolver extends AbstractObjectTypeResolver
{
    use IntrospectionTypeResolverTrait;
}
