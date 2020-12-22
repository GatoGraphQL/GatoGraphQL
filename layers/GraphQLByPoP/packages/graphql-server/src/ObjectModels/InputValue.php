<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractSchemaDefinitionReferenceObject;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasTypeSchemaDefinitionReferenceTrait;

class InputValue extends AbstractSchemaDefinitionReferenceObject
{
    use HasTypeSchemaDefinitionReferenceTrait;

    public function initializeTypeDependencies(): void
    {
        $this->initType();
    }
    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_NAME];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION];
    }
    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        // The default value must be returned as a JSON encoded string
        // From the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-IAJbTHHAABABM7kV):
        // "defaultValue may return a String encoding (using the GraphQL language) of the default value used by this input value in the condition a value is not provided at runtime. If this input value has no default value, returns null."
        if ($defaultValue = $this->schemaDefinition[SchemaDefinition::ARGNAME_DEFAULT_VALUE] ?? null) {
            return json_encode($defaultValue);
        }
        return null;
    }
}
