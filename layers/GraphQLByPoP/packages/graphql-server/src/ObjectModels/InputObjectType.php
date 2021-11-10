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

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath)
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath);

        $this->initInputValues($fullSchemaDefinition, $schemaDefinitionPath);
    }
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
    public function getInputFields(): array
    {
        return $this->inputValues;
    }
    public function getInputFieldIDs(): array
    {
        return array_map(
            function (InputValue $inputValue) {
                return $inputValue->getID();
            },
            $this->getInputFields()
        );
    }
}
