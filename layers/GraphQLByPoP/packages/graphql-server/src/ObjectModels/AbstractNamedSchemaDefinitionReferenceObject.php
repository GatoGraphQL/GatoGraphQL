<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;

abstract class AbstractNamedSchemaDefinitionReferenceObject extends AbstractSchemaDefinitionReferenceObject implements NamedSchemaDefinitionReferenceObjectInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $schemaDefinition;

    /**
     * Build a new Schema Definition Reference Object
     */
    public function __construct(/** @var array<string, mixed> */
        array &$fullSchemaDefinition,
        /** @var string[] */
        protected array $schemaDefinitionPath,
    ) {
        $this->id = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID($schemaDefinitionPath);

        // Retrieve this element's schema definition by iterating down its path starting from the root of the full schema definition
        $schemaDefinitionPointer = &$fullSchemaDefinition;
        foreach ($schemaDefinitionPath as $pathLevel) {
            $schemaDefinitionPointer = &$schemaDefinitionPointer[$pathLevel];
        }
        $this->schemaDefinition = &$schemaDefinitionPointer;

        parent::__construct();
    }

    public function getSchemaDefinition(): array
    {
        return $this->schemaDefinition;
    }

    public function getSchemaDefinitionPath(): array
    {
        return $this->schemaDefinitionPath;
    }
}
