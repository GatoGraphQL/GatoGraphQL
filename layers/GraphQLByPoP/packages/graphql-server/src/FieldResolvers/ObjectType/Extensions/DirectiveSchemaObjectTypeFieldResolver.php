<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveTypeEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class DirectiveSchemaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?DirectiveTypeEnumTypeResolver $directiveTypeEnumTypeResolver = null;
    private ?DirectiveRegistryInterface $directiveRegistry = null;

    final public function setDirectiveTypeEnumTypeResolver(DirectiveTypeEnumTypeResolver $directiveTypeEnumTypeResolver): void
    {
        $this->directiveTypeEnumTypeResolver = $directiveTypeEnumTypeResolver;
    }
    final protected function getDirectiveTypeEnumTypeResolver(): DirectiveTypeEnumTypeResolver
    {
        return $this->directiveTypeEnumTypeResolver ??= $this->instanceManager->getInstance(DirectiveTypeEnumTypeResolver::class);
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
            'type',
        ];
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'type' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'type' => $this->getTranslationAPI()->__('The directive type (custom property)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        /** @var Directive */
        $directive = $object;
        return match ($fieldName) {
            'type' => ($directive->getExtensions())[SchemaDefinition::DIRECTIVE_TYPE],
            default => parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'type' => $this->getDirectiveTypeEnumTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
