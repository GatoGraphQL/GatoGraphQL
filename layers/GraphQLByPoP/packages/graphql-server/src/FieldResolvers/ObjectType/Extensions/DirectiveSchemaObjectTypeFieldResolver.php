<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class DirectiveSchemaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver = null;
    private ?DirectiveRegistryInterface $directiveRegistry = null;

    final public function setDirectiveKindEnumTypeResolver(DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver): void
    {
        $this->directiveKindEnumTypeResolver = $directiveKindEnumTypeResolver;
    }
    final protected function getDirectiveKindEnumTypeResolver(): DirectiveKindEnumTypeResolver
    {
        return $this->directiveKindEnumTypeResolver ??= $this->instanceManager->getInstance(DirectiveKindEnumTypeResolver::class);
    }
    final public function setDirectiveRegistry(DirectiveRegistryInterface $directiveRegistry): void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    final protected function getDirectiveRegistry(): DirectiveRegistryInterface
    {
        return $this->directiveRegistry ??= $this->instanceManager->getInstance(DirectiveRegistryInterface::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            DirectiveObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'kind',
        ];
    }

    public function skipExposingFieldInSchema(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): bool
    {
        return match ($fieldName) {
            'kind' => true,
            default => parent::skipExposingFieldInSchema($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'kind' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'kind' => $this->__('The directive type (custom property)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        array $options = []
    ): mixed {
        /** @var Directive */
        $directive = $object;
        return match ($fieldName) {
            'kind' => $directive->getKind(),
            default => parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'kind' => $this->getDirectiveKindEnumTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
