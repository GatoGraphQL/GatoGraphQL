<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\ObjectModels\EnumType;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\InputObjectType;
use GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\ScalarType;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds;
use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\TypeKindEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\EnumValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\FieldObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\InputValueObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\NamedTypeExtensionsObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\TypeObjectTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class TypeObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?NamedTypeExtensionsObjectTypeResolver $namedTypeExtensionsObjectTypeResolver = null;
    private ?FieldObjectTypeResolver $fieldObjectTypeResolver = null;
    private ?TypeObjectTypeResolver $typeObjectTypeResolver = null;
    private ?EnumValueObjectTypeResolver $enumValueObjectTypeResolver = null;
    private ?InputValueObjectTypeResolver $inputValueObjectTypeResolver = null;
    private ?TypeKindEnumTypeResolver $typeKindEnumTypeResolver = null;

    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
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
    final protected function getNamedTypeExtensionsObjectTypeResolver(): NamedTypeExtensionsObjectTypeResolver
    {
        if ($this->namedTypeExtensionsObjectTypeResolver === null) {
            /** @var NamedTypeExtensionsObjectTypeResolver */
            $namedTypeExtensionsObjectTypeResolver = $this->instanceManager->getInstance(NamedTypeExtensionsObjectTypeResolver::class);
            $this->namedTypeExtensionsObjectTypeResolver = $namedTypeExtensionsObjectTypeResolver;
        }
        return $this->namedTypeExtensionsObjectTypeResolver;
    }
    final protected function getFieldObjectTypeResolver(): FieldObjectTypeResolver
    {
        if ($this->fieldObjectTypeResolver === null) {
            /** @var FieldObjectTypeResolver */
            $fieldObjectTypeResolver = $this->instanceManager->getInstance(FieldObjectTypeResolver::class);
            $this->fieldObjectTypeResolver = $fieldObjectTypeResolver;
        }
        return $this->fieldObjectTypeResolver;
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
    final protected function getEnumValueObjectTypeResolver(): EnumValueObjectTypeResolver
    {
        if ($this->enumValueObjectTypeResolver === null) {
            /** @var EnumValueObjectTypeResolver */
            $enumValueObjectTypeResolver = $this->instanceManager->getInstance(EnumValueObjectTypeResolver::class);
            $this->enumValueObjectTypeResolver = $enumValueObjectTypeResolver;
        }
        return $this->enumValueObjectTypeResolver;
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
    final protected function getTypeKindEnumTypeResolver(): TypeKindEnumTypeResolver
    {
        if ($this->typeKindEnumTypeResolver === null) {
            /** @var TypeKindEnumTypeResolver */
            $typeKindEnumTypeResolver = $this->instanceManager->getInstance(TypeKindEnumTypeResolver::class);
            $this->typeKindEnumTypeResolver = $typeKindEnumTypeResolver;
        }
        return $this->typeKindEnumTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            TypeObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'kind',
            'name',
            'description',
            'fields',
            'interfaces',
            'possibleTypes',
            'enumValues',
            'inputFields',
            'ofType',
            'specifiedByURL',
            'isOneOf',
            'extensions',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'name',
            'description',
            'specifiedByURL'
                => $this->getStringScalarTypeResolver(),
            'extensions'
                => $this->getNamedTypeExtensionsObjectTypeResolver(),
            'fields'
                => $this->getFieldObjectTypeResolver(),
            'interfaces',
            'possibleTypes',
            'ofType'
                => $this->getTypeObjectTypeResolver(),
            'enumValues'
                => $this->getEnumValueObjectTypeResolver(),
            'inputFields'
                => $this->getInputValueObjectTypeResolver(),
            'kind'
                => $this->getTypeKindEnumTypeResolver(),
            'isOneOf'
                => $this->getBooleanScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'kind',
            'isOneOf'
                => SchemaTypeModifiers::NON_NULLABLE,
            'fields',
            'interfaces',
            'possibleTypes',
            'enumValues',
            'inputFields'
                => SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'kind' => $this->__('Type\'s kind as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACqBBCvBAtrC)', 'graphql-server'),
            'name' => $this->__('Type\'s name as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACvBBCyBH6rd)', 'graphql-server'),
            'description' => $this->__('Type\'s description as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACyBIC1BHnjL)', 'graphql-server'),
            'fields' => $this->__('Type\'s fields (available for Object and Interface types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC3BBCnCA8pY)', 'graphql-server'),
            'interfaces' => $this->__('Type\'s interfaces (available for Object type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACpCBCxCA7tB)', 'graphql-server'),
            'possibleTypes' => $this->__('Type\'s possible types (available for Interface and Union types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACzCBC7CA0vN)', 'graphql-server'),
            'enumValues' => $this->__('Type\'s enum values (available for Enum type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC9CDD_CAA2lB)', 'graphql-server'),
            'inputFields' => $this->__('Type\'s input Fields (available for InputObject type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLAuDABCBIu9N)', 'graphql-server'),
            'ofType' => $this->__('The type of the nested type (available for NonNull and List types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLA4DABCBIu9N)', 'graphql-server'),
            'specifiedByURL' => $this->__('A scalar specification URL (a String (in the form of a URL) for custom scalars, otherwise must be null) as defined by the GraphQL spec (https://spec.graphql.org/draft/#sel-IAJXNFA0EABABL9N)', 'graphql-server'),
            'isOneOf' => $this->__('`true` for OneOf Input Objects, `false` otherwise', 'graphql-server'),
            'extensions' => $this->__('Extensions (custom metadata) added to the GraphQL type (for all \'named\' types: Object, Interface, Union, Scalar, Enum and InputObject) (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'fields',
            'enumValues' => [
                'includeDeprecated' => $this->getBooleanScalarTypeResolver(),
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ($fieldArgName) {
            'includeDeprecated' => $this->__('Include deprecated fields?', 'graphql-server'),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ($fieldArgName) {
            'includeDeprecated' => false,
            default => parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var TypeInterface */
        $type = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'kind':
                return $type->getKind();
            case 'name':
                return $type->getName();
            case 'description':
                return $type->getDescription();
            case 'fields':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC1BJC3BAn6e):
                // "should be non-null for OBJECT and INTERFACE only, must be null for the others"
                if ($type instanceof HasFieldsTypeInterface) {
                    /**
                     * Only include the global fields for Objects!
                     * (i.e. do not for Interfaces)
                     */
                    $includeGlobal = $type->getKind() === TypeKinds::OBJECT;
                    return $type->getFieldIDs(
                        $fieldDataAccessor->getValue('includeDeprecated') ?? false,
                        $includeGlobal
                    );
                }
                return null;
            case 'interfaces':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACnCCCpCA4yV):
                // "should be non-null for OBJECT only, must be null for the others"
                if ($type instanceof HasInterfacesTypeInterface) {
                    return $type->getInterfaceIDs();
                }
                return null;
            case 'possibleTypes':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACxCCCzCA_9R):
                // "should be non-null for INTERFACE and UNION only, always null for the others"
                if ($type instanceof HasPossibleTypesTypeInterface) {
                    return $type->getPossibleTypeIDs();
                }
                return null;
            case 'enumValues':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC7CCC9CA2nT):
                // "should be non-null for ENUM only, must be null for the others"
                if ($type instanceof EnumType) {
                    return $type->getEnumValueIDs($fieldDataAccessor->getValue('includeDeprecated') ?? false);
                }
                return null;
            case 'inputFields':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLAuDABCBIu9N):
                // "should be non-null for INPUT_OBJECT only, must be null for the others"
                if ($type instanceof InputObjectType) {
                    return $type->getInputFieldIDs();
                }
                return null;
            case 'ofType':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLA4DABCBIu9N):
                // "should be non-null for NON_NULL and LIST only, must be null for the others"
                if ($type instanceof WrappingTypeInterface) {
                    return $type->getWrappedTypeID();
                }
                return null;
            case 'specifiedByURL':
                // From GraphQL spec (https://spec.graphql.org/draft/#sel-GAJXNFACzEDD1EAA_pc):
                // "may be non-null for custom SCALAR, otherwise null"
                if ($type instanceof ScalarType) {
                    return $type->getSpecifiedByURL();
                }
                return null;
            case 'isOneOf':
                if ($type instanceof InputObjectType) {
                    return $type->isOneOfInputObjectType();
                }
                return false;
            case 'extensions':
                // Custom development: this field is not in GraphQL spec yet!
                // @see https://github.com/graphql/graphql-spec/issues/300
                // Implementation based on the one by GraphQL Java
                // @see https://github.com/graphql-java/graphql-java/pull/2221
                // Non-null for named types, null for wrapping types (Non-Null and List)
                if ($type instanceof NamedTypeInterface) {
                    return $type->getExtensions()->getID();
                }
                return null;
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
