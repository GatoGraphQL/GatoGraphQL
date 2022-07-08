<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class FieldObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?FieldExtensionsObjectTypeResolver $fieldExtensionsObjectTypeResolver = null;
    private ?InputValueObjectTypeResolver $inputValueObjectTypeResolver = null;
    private ?TypeObjectTypeResolver $typeObjectTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setFieldExtensionsObjectTypeResolver(FieldExtensionsObjectTypeResolver $fieldExtensionsObjectTypeResolver): void
    {
        $this->fieldExtensionsObjectTypeResolver = $fieldExtensionsObjectTypeResolver;
    }
    final protected function getFieldExtensionsObjectTypeResolver(): FieldExtensionsObjectTypeResolver
    {
        return $this->fieldExtensionsObjectTypeResolver ??= $this->instanceManager->getInstance(FieldExtensionsObjectTypeResolver::class);
    }
    final public function setInputValueObjectTypeResolver(InputValueObjectTypeResolver $inputValueObjectTypeResolver): void
    {
        $this->inputValueObjectTypeResolver = $inputValueObjectTypeResolver;
    }
    final protected function getInputValueObjectTypeResolver(): InputValueObjectTypeResolver
    {
        return $this->inputValueObjectTypeResolver ??= $this->instanceManager->getInstance(InputValueObjectTypeResolver::class);
    }
    final public function setTypeObjectTypeResolver(TypeObjectTypeResolver $typeObjectTypeResolver): void
    {
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
    }
    final protected function getTypeObjectTypeResolver(): TypeObjectTypeResolver
    {
        return $this->typeObjectTypeResolver ??= $this->instanceManager->getInstance(TypeObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            FieldObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'args',
            'type',
            'isDeprecated',
            'deprecationReason',
            'extensions',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->getStringScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'isDeprecated' => $this->getBooleanScalarTypeResolver(),
            'deprecationReason' => $this->getStringScalarTypeResolver(),
            'extensions' => $this->getFieldExtensionsObjectTypeResolver(),
            'args' => $this->getInputValueObjectTypeResolver(),
            'type' => $this->getTypeObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            'type',
            'isDeprecated',
            'extensions'
                => SchemaTypeModifiers::NON_NULLABLE,
            'args'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Field\'s name', 'graphql-server'),
            'description' => $this->__('Field\'s description', 'graphql-server'),
            'args' => $this->__('Field arguments', 'graphql-server'),
            'type' => $this->__('Type to which the field belongs', 'graphql-server'),
            'isDeprecated' => $this->__('Is the field deprecated?', 'graphql-server'),
            'deprecationReason' => $this->__('Why was the field deprecated?', 'graphql-server'),
            'extensions' => $this->__('Extensions (custom metadata) added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var Field */
        $fieldObject = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $fieldObject->getName();
            case 'description':
                return $fieldObject->getDescription();
            case 'args':
                return $fieldObject->getArgIDs();
            case 'type':
                return $fieldObject->getTypeID();
            case 'isDeprecated':
                return $fieldObject->isDeprecated();
            case 'deprecationReason':
                return $fieldObject->getDeprecationMessage();
            case 'extensions':
                return $fieldObject->getExtensions()->getID();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
