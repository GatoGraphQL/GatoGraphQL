<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class NamedTypeExtensions extends AbstractSchemaDefinitionReferenceObject
{
    public function getTypeElementName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ELEMENT_NAME];
    }

    public function getTypeNamespacedName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAMESPACED_NAME];
    }

    /**
     * Enum-like "possible values" for EnumString type resolvers, `null` otherwise
     *
     * @return string[]|null
     */
    public function getTypePossibleValues(): ?array
    {
        return $this->schemaDefinition[SchemaDefinition::POSSIBLE_VALUES] ?? null;
    }

    /**
     * @see https://github.com/graphql/graphql-spec/pull/825
     *
     * > OneOf Input Objects are a special variant of Input Objects
     * > where the type system asserts that exactly one of the fields
     * > must be set and non-null, all others being omitted.
     * > This is represented in introspection with the
     * __Type.isOneOf: Boolean field.
     */
    public function getTypeIsOneOfInputObjectType(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::IS_ONE_OF] ?? false;
    }
}
