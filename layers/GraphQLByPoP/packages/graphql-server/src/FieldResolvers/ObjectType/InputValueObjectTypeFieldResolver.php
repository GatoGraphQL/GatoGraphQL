<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\ObjectModels\InputValue;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class InputValueObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?TypeObjectTypeResolver $typeObjectTypeResolver = null;
    private ?InputValueExtensionsObjectTypeResolver $inputValueExtensionsObjectTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setTypeObjectTypeResolver(TypeObjectTypeResolver $typeObjectTypeResolver): void
    {
        $this->typeObjectTypeResolver = $typeObjectTypeResolver;
    }
    final protected function getTypeObjectTypeResolver(): TypeObjectTypeResolver
    {
        return $this->typeObjectTypeResolver ??= $this->instanceManager->getInstance(TypeObjectTypeResolver::class);
    }
    final public function setInputValueExtensionsObjectTypeResolver(InputValueExtensionsObjectTypeResolver $inputValueExtensionsObjectTypeResolver): void
    {
        $this->inputValueExtensionsObjectTypeResolver = $inputValueExtensionsObjectTypeResolver;
    }
    final protected function getInputValueExtensionsObjectTypeResolver(): InputValueExtensionsObjectTypeResolver
    {
        return $this->inputValueExtensionsObjectTypeResolver ??= $this->instanceManager->getInstance(InputValueExtensionsObjectTypeResolver::class);
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            InputValueObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'description',
            'type',
            'defaultValue',
            'extensions',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->getStringScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'defaultValue' => $this->getStringScalarTypeResolver(),
            'type' => $this->getTypeObjectTypeResolver(),
            'extensions' => $this->getInputValueExtensionsObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            'type',
            'extensions'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Input value\'s name as defined by the GraphQL spec', 'graphql-server'),
            'description' => $this->__('Input value\'s description', 'graphql-server'),
            'type' => $this->__('Type of the input value', 'graphql-server'),
            'defaultValue' => $this->__('Default value of the input value', 'graphql-server'),
            'extensions' => $this->__('Extensions (custom metadata) added to the input (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var InputValue */
        $inputValue = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $inputValue->getName();
            case 'description':
                return $inputValue->getDescription();
            case 'type':
                return $inputValue->getTypeID();
            case 'defaultValue':
                return $inputValue->getDefaultValue();
            case 'extensions':
                return $inputValue->getExtensions()->getID();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
