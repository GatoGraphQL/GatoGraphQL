<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Registries\FieldDirectiveRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class DirectiveSchemaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver = null;
    private ?FieldDirectiveRegistryInterface $fieldDirectiveRegistry = null;

    final public function setDirectiveKindEnumTypeResolver(DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver): void
    {
        $this->directiveKindEnumTypeResolver = $directiveKindEnumTypeResolver;
    }
    final protected function getDirectiveKindEnumTypeResolver(): DirectiveKindEnumTypeResolver
    {
        /** @var DirectiveKindEnumTypeResolver */
        return $this->directiveKindEnumTypeResolver ??= $this->instanceManager->getInstance(DirectiveKindEnumTypeResolver::class);
    }
    final public function setFieldDirectiveRegistry(FieldDirectiveRegistryInterface $fieldDirectiveRegistry): void
    {
        $this->fieldDirectiveRegistry = $fieldDirectiveRegistry;
    }
    final protected function getFieldDirectiveRegistry(): FieldDirectiveRegistryInterface
    {
        /** @var FieldDirectiveRegistryInterface */
        return $this->fieldDirectiveRegistry ??= $this->instanceManager->getInstance(FieldDirectiveRegistryInterface::class);
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            DirectiveObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var Directive */
        $directive = $object;
        return match ($fieldDataAccessor->getFieldName()) {
            'kind' => $directive->getKind(),
            default => parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore),
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
