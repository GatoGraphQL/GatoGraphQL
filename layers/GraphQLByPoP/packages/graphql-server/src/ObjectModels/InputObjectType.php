<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class InputObjectType extends AbstractNamedType
{
    /**
     * @var InputValue[]
     */
    protected array $inputValues;

    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $this->initInputValues($fullSchemaDefinition, $schemaDefinitionPath);
    }
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    protected function initInputValues(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->inputValues = [];
        if ($inputValues = $this->schemaDefinition[SchemaDefinition::INPUT_FIELDS] ?? null) {
            /** @var string $inputValueName */
            foreach (array_keys($inputValues) as $inputValueName) {
                /** @var string[] */
                $inputValueSchemaDefinitionPath = array_merge(
                    $schemaDefinitionPath,
                    [
                        SchemaDefinition::INPUT_FIELDS,
                        $inputValueName,
                    ]
                );
                $this->inputValues[] = new InputValue(
                    $fullSchemaDefinition,
                    $inputValueSchemaDefinitionPath
                );
            }
        }
    }

    public function getKind(): string
    {
        return TypeKinds::INPUT_OBJECT;
    }
    /**
     * @return InputValue[]
     */
    public function getInputFields(): array
    {
        return $this->inputValues;
    }
    /**
     * @return string[]
     */
    public function getInputFieldIDs(): array
    {
        return array_map(
            function (InputValue $inputValue): string {
                return $inputValue->getID();
            },
            $this->getInputFields()
        );
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
    public function isOneOfInputObjectType(): bool
    {
        return $this->schemaDefinition[SchemaDefinition::IS_ONE_OF] ?? false;
    }
}
