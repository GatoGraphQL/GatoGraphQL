<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\DummySchema\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class HelloWorldVersion100RootObjectTypeFieldResolver extends AbstractHelloWorldRootObjectTypeFieldResolver
{
    public function getFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return '1.0.0';
    }

    public function getPriorityToAttachToClasses(): int
    {
        return 20;
    }
}
