<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\FieldResolvers;

use GraphQLByPoP\GraphQLServer\Enums\TypeKindEnum;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractNestableType;
use GraphQLByPoP\GraphQLServer\ObjectModels\EnumType;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasInterfacesTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasPossibleTypesTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\InputObjectType;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumValueTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\FieldTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\InputValueTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\TypeTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\FieldResolvers\EnumTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

class TypeFieldResolver extends AbstractDBDataFieldResolver
{
    use EnumTypeFieldSchemaDefinitionResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(TypeTypeResolver::class);
    }

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
            'extensions',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'kind' => SchemaDefinition::TYPE_ENUM,
            'name' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'fields' => SchemaDefinition::TYPE_ID,
            'interfaces' => SchemaDefinition::TYPE_ID,
            'possibleTypes' => SchemaDefinition::TYPE_ID,
            'enumValues' => SchemaDefinition::TYPE_ID,
            'inputFields' => SchemaDefinition::TYPE_ID,
            'ofType' => SchemaDefinition::TYPE_ID,
            'extensions' => SchemaDefinition::TYPE_MIXED,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'kind'
                => SchemaTypeModifiers::NON_NULLABLE,
            'fields',
            'interfaces',
            'possibleTypes',
            'enumValues',
            'inputFields',
            'extensions'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    protected function getSchemaDefinitionEnumName(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'kind':
                /**
                 * @var TypeKindEnum
                 */
                $typeKindEnum = $this->instanceManager->getInstance(TypeKindEnum::class);
                return $typeKindEnum->getName();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValues(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        switch ($fieldName) {
            case 'kind':
                /**
                 * @var TypeKindEnum
                 */
                $typeKindEnum = $this->instanceManager->getInstance(TypeKindEnum::class);
                return $typeKindEnum->getValues();
        }
        return null;
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'kind' => $this->translationAPI->__('Type\'s kind as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACqBBCvBAtrC)', 'graphql-server'),
            'name' => $this->translationAPI->__('Type\'s name as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACvBBCyBH6rd)', 'graphql-server'),
            'description' => $this->translationAPI->__('Type\'s description as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACyBIC1BHnjL)', 'graphql-server'),
            'fields' => $this->translationAPI->__('Type\'s fields (available for Object and Interface types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC3BBCnCA8pY)', 'graphql-server'),
            'interfaces' => $this->translationAPI->__('Type\'s interfaces (available for Object type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACpCBCxCA7tB)', 'graphql-server'),
            'possibleTypes' => $this->translationAPI->__('Type\'s possible types (available for Interface and Union types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACzCBC7CA0vN)', 'graphql-server'),
            'enumValues' => $this->translationAPI->__('Type\'s enum values (available for Enum type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC9CDD_CAA2lB)', 'graphql-server'),
            'inputFields' => $this->translationAPI->__('Type\'s input Fields (available for InputObject type only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLAuDABCBIu9N)', 'graphql-server'),
            'ofType' => $this->translationAPI->__('The type of the nested type (available for NonNull and List types only) as defined by the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-HAJbLA4DABCBIu9N)', 'graphql-server'),
            'extensions' => $this->translationAPI->__('Custom metadata added to the field (see: https://github.com/graphql/graphql-spec/issues/300#issuecomment-504734306 and below comments, and https://github.com/graphql/graphql-js/issues/1527)', 'graphql-server'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'fields':
            case 'enumValues':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'includeDeprecated',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_BOOL,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Include deprecated fields?', 'graphql-server'),
                            SchemaDefinition::ARGNAME_DEFAULT_VALUE => false,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $type = $resultItem;
        switch ($fieldName) {
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
                    return $type->getFieldIDs($fieldArgs['includeDeprecated']);
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
                    return $type->getEnumValueIDs($fieldArgs['includeDeprecated']);
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
                if ($type instanceof AbstractNestableType) {
                    return $type->getNestedTypeID();
                }
                return null;
            case 'extensions':
                return $type->getExtensions();
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'fields':
                return FieldTypeResolver::class;
            case 'interfaces':
            case 'possibleTypes':
            case 'ofType':
                return TypeTypeResolver::class;
            case 'enumValues':
                return EnumValueTypeResolver::class;
            case 'inputFields':
                return InputValueTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
