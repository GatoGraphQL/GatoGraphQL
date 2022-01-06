<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\NodeInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class NodeObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?NodeInterfaceTypeFieldResolver $nodeInterfaceTypeFieldResolver = null;

    final public function setNodeInterfaceTypeFieldResolver(NodeInterfaceTypeFieldResolver $nodeInterfaceTypeFieldResolver): void
    {
        $this->nodeInterfaceTypeFieldResolver = $nodeInterfaceTypeFieldResolver;
    }
    final protected function getNodeInterfaceTypeFieldResolver(): NodeInterfaceTypeFieldResolver
    {
        return $this->nodeInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(NodeInterfaceTypeFieldResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getNodeInterfaceTypeFieldResolver(),
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'id',
            'self',
        ];
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'self' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'self' => $this->__('The same object', 'pop-component-model'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(ObjectTypeResolverInterface $objectTypeResolver, object $object, string $fieldName, array $fieldArgs, ?array $variables = null, ?array $expressions = null, array $options = []): mixed
    {
        return match ($fieldName) {
            'id',
            'self'
                => $objectTypeResolver->getID($object),
            default
                => parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'self' => $objectTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
