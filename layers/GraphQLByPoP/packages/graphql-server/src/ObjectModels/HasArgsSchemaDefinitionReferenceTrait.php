<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoPAPI\API\Schema\SchemaDefinition;

trait HasArgsSchemaDefinitionReferenceTrait
{
    /**
     * @var InputValue[]
     */
    protected array $args;

    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    protected function initArgs(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->args = [];
        if ($args = $this->schemaDefinition[SchemaDefinition::ARGS] ?? null) {
            /** @var string $fieldArgName */
            foreach (array_keys($args) as $fieldArgName) {
                /** @var string[] */
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
            function (InputValue $inputValue): string {
                return $inputValue->getID();
            },
            $this->getArgs()
        );
    }
}
