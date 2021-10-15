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
    /**
     * Implementation of "args" field from the Field object
     *
     * @return InputValue[]
     *
     * @see https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACsEIDuEAA-vb
     */
    public function getArgs(): array
    {
        return $this->args;
    }
    /**
     * @return string[]
     */
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
