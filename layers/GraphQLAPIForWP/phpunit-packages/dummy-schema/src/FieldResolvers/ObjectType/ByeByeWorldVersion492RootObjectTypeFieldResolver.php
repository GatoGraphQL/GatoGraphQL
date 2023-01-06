<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\DummySchema\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class ByeByeWorldVersion492RootObjectTypeFieldResolver extends AbstractHelloWorldRootObjectTypeFieldResolver
{
    public function getHelloWorldFieldName(): string
    {
        return 'byeByeWorld';
    }

    public function getFieldVersion(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return '4.9.2';
    }

    public function getPriorityToAttachToClasses(): int
    {
        return 30;
    }
}
