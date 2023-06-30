<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

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
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setFieldExtensionsObjectTypeResolver(FieldExtensionsObjectTypeResolver $fieldExtensionsObjectTypeResolver): void
    {
        $this->fieldExtensionsObjectTypeResolver = $fieldExtensionsObjectTypeResolver;
    }
    final protected function getFieldExtensionsObjectTypeResolver(): FieldExtensionsObjectTypeResolver
    {
        if ($this->fieldExtensionsObjectTypeResolver === null) {
            /** @var FieldExtensionsObjectTypeResolver */
            $fieldExtensionsObjectTypeResolver = $this->instanceManager->getInstance(FieldExtensionsObjectTypeResolver::class);
            $this->fieldExtensionsObjectTypeResolver = $fieldExtensionsObjectTypeResolver;
        }
        return $this->fieldExtensionsObjectTypeResolver;
    }
    final public function setInputValueObjectTypeResolver(InputValueObjectTypeResolver $inputValueObjectTypeResolver): void
    {
        $this->inputValueObjectTypeResolver = $inputValueObjectTypeResolver;
    }
    final protected function getInputValueObjectTypeResolver(): InputValueObjectTypeResolver
    {
        if ($this->inputValueObjectTypeResolver === null) {
            /** @var InputValueObjectTypeResolver */
            $inputValueObjectTypeResolver = $this->instanceManager->getInstance(InputValueObjectTypeResolver::class);
            $this->inputValueObjectTypeResolver = $inputValueObjectTypeResolver;
        }
        return $this->inputValueObjectTypeResolver;
    }
    final public function setTypeObjectTypeResolver(TypeObjectTypeResolver $typeObjectTypeResolver): void
    {
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
    }
    final protected function getTypeObjectTypeResolver(): TypeObjectTypeResolver
    {
        if ($this->typeObjectTypeResolver === null) {
            /** @var TypeObjectTypeResolver */
            $typeObjectTypeResolver = $this->instanceManager->getInstance(TypeObjectTypeResolver::class);
            $this->typeObjectTypeResolver = $typeObjectTypeResolver;
        }
        return $this->typeObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            FieldObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
