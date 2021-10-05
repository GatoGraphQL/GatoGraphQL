<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\API\Schema\SchemaDefinition;

trait HasArgsSchemaDefinitionReferenceTrait
{
    /**
     * @var InputValue[]
     */
    protected array $args;

    protected function initArgs(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->args = [];
        if ($args = $this->schemaDefinition[SchemaDefinition::ARGS] ?? null) {
            foreach (array_keys($args) as $fieldArgName) {
                $fieldArgSchemaDefinitionPath = array_merge(
                    $schemaDefinitionPath,
                    [
                        SchemaDefinition::ARGS,
                        $fieldArgName,
                    ]
                );
                $this->args[] = new InputValue(
                    $fullSchemaDefinition,
                    $fieldArgSchemaDefinitionPath
                );
            }
        }
    }
    public function initializeArgsTypeDependencies(): void
    {
        foreach ($this->args as $arg) {
            $arg->initializeTypeDependencies();
        }
    }
    /**
     * Implementation of "args" field from the Field object (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACsEIDuEAA-vb)
     *
     * @return array of InputValue type
     */
    public function getArgs(): array
    {
        return $this->args;
    }
    public function getArgIDs(): array
    {
        return array_map(
            function (InputValue $inputValue) {
                return $inputValue->getID();
            },
            $this->getArgs()
        );
    }
}
