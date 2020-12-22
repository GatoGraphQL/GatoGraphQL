<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\ObjectModels\InputValue;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractDynamicType;
use PoP\ComponentModel\Schema\SchemaDefinition;

class InputObjectType extends AbstractDynamicType
{
    /**
     * @var InputValue[]
     */
    protected array $inputValues;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);

        $this->initInputValues($fullSchemaDefinition, $schemaDefinitionPath);
        foreach ($this->inputValues as $inputValue) {
            $inputValue->initializeTypeDependencies();
        }
    }
    protected function initInputValues(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->inputValues = [];
        if ($inputValues = $this->schemaDefinition[SchemaDefinition::ARGNAME_ARGS] ?? null) {
            foreach (array_keys($inputValues) as $inputValueName) {
                $inputValueSchemaDefinitionPath = array_merge(
                    $schemaDefinitionPath,
                    [
                        SchemaDefinition::ARGNAME_ARGS,
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

    protected function getDynamicTypeNamePropertyInSchema(): string
    {
        return SchemaDefinition::ARGNAME_INPUT_OBJECT_NAME;
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
