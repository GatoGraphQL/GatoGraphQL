<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\Directive;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveLocationEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\DirectiveObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class DirectiveObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?InputValueObjectTypeResolver $inputValueObjectTypeResolver = null;
    private ?DirectiveLocationEnumTypeResolver $directiveLocationEnumTypeResolver = null;
    private ?DirectiveExtensionsObjectTypeResolver $directiveExtensionsObjectTypeResolver = null;

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
    final public function setDirectiveLocationEnumTypeResolver(DirectiveLocationEnumTypeResolver $directiveLocationEnumTypeResolver): void
    {
        $this->directiveLocationEnumTypeResolver = $directiveLocationEnumTypeResolver;
    }
    final protected function getDirectiveLocationEnumTypeResolver(): DirectiveLocationEnumTypeResolver
    {
        if ($this->directiveLocationEnumTypeResolver === null) {
            /** @var DirectiveLocationEnumTypeResolver */
            $directiveLocationEnumTypeResolver = $this->instanceManager->getInstance(DirectiveLocationEnumTypeResolver::class);
            $this->directiveLocationEnumTypeResolver = $directiveLocationEnumTypeResolver;
        }
        return $this->directiveLocationEnumTypeResolver;
    }
    final public function setDirectiveExtensionsObjectTypeResolver(DirectiveExtensionsObjectTypeResolver $directiveExtensionsObjectTypeResolver): void
    {
        $this->directiveExtensionsObjectTypeResolver = $directiveExtensionsObjectTypeResolver;
    }
    final protected function getDirectiveExtensionsObjectTypeResolver(): DirectiveExtensionsObjectTypeResolver
    {
        if ($this->directiveExtensionsObjectTypeResolver === null) {
            /** @var DirectiveExtensionsObjectTypeResolver */
            $directiveExtensionsObjectTypeResolver = $this->instanceManager->getInstance(DirectiveExtensionsObjectTypeResolver::class);
            $this->directiveExtensionsObjectTypeResolver = $directiveExtensionsObjectTypeResolver;
        }
        return $this->directiveExtensionsObjectTypeResolver;
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
            'name',
            'description',
            'args',
            'locations',
            'isRepeatable',
            'extensions',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name' => $this->getStringScalarTypeResolver(),
            'description' => $this->getStringScalarTypeResolver(),
            'isRepeatable' => $this->getBooleanScalarTypeResolver(),
            'args' => $this->getInputValueObjectTypeResolver(),
            'locations' => $this->getDirectiveLocationEnumTypeResolver(),
            'extensions' => $this->getDirectiveExtensionsObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'name',
            'isRepeatable',
            'extensions'
                => SchemaTypeModifiers::NON_NULLABLE,
            'locations',
            'args'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'name' => $this->__('Directive\'s name', 'graphql-server'),
            'description' => $this->__('Directive\'s description', 'graphql-server'),
            'args' => $this->__('Directive\'s arguments', 'graphql-server'),
            'locations' => $this->__('The locations where the directive may be placed', 'graphql-server'),
            'isRepeatable' => $this->__('Can the directive be executed more than once in the same field?', 'graphql-server'),
            'extensions' => $this->__('Extensions (custom metadata) added to the directive (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
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
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $directive->getName();
            case 'description':
                return $directive->getDescription();
            case 'args':
                return $directive->getArgIDs();
            case 'locations':
                return $directive->getLocations();
            case 'isRepeatable':
                return $directive->isRepeatable();
            case 'extensions':
                return $directive->getExtensions()->getID();
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
