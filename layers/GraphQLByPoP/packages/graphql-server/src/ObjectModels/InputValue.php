<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class InputValue extends AbstractSchemaDefinitionReferenceObject
{
    use HasTypeSchemaDefinitionReferenceTrait;

    protected InputValueExtensions $inputValueExtensions;

    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        /** @var string[] */
        $inputValueExtensionsSchemaDefinitionPath = array_merge(
            $schemaDefinitionPath,
            [
                SchemaDefinition::EXTENSIONS,
            ]
        );
        $this->inputValueExtensions = new InputValueExtensions($fullSchemaDefinition, $inputValueExtensionsSchemaDefinitionPath);
    }

    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::NAME];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::DESCRIPTION] ?? null;
    }

    /**
     * The default value must be returned as a JSON encoded string.
     *
     * From the GraphQL spec:
     *
     *   defaultValue may return a String encoding (using the GraphQL language)
     *   of the default value used by this input value in the condition a value
     *   is not provided at runtime. If this input value has no default value,
     *   returns null.
     *
     * @see http://spec.graphql.org/draft/#sec-The-__InputValue-Type
     */
    public function getDefaultValue(): ?string
    {
        if ($defaultValue = $this->schemaDefinition[SchemaDefinition::DEFAULT_VALUE] ?? null) {
            return (string)json_encode($defaultValue);
        }
        return null;
    }

    public function getExtensions(): InputValueExtensions
    {
        return $this->inputValueExtensions;
    }
}
