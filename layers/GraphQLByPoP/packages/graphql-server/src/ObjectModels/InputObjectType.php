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
            foreach (array_keys($inputValues) as $inputValueName) {
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
}
