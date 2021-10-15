<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;

abstract class AbstractSchemaDefinitionReferenceObject implements SchemaDefinitionReferenceObjectInterface
{
    protected string $id;
    /**
     * @var array<string, mixed>
     */
    protected array $schemaDefinition;

    /**
     * Build a new Schema Definition Reference Object
     */
    public function __construct(/** @var array<string, mixed> */
        protected array &$fullSchemaDefinition,
        /** @var string[] */
        protected array $schemaDefinitionPath,
    ) {
        // Retrieve this element's schema definition by iterating down its path starting from the root of the full schema definition
        $schemaDefinitionPointer = &$fullSchemaDefinition;
        foreach ($schemaDefinitionPath as $pathLevel) {
            $schemaDefinitionPointer = &$schemaDefinitionPointer[$pathLevel];
        }
        $this->schemaDefinition = $schemaDefinitionPointer;

        // Register the object, and get back its ID
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        $this->id = $schemaDefinitionReferenceRegistry->registerSchemaDefinitionReferenceObject($this);
    }

    public function getSchemaDefinition(): array
    {
        return $this->schemaDefinition;
    }

    public function getSchemaDefinitionPath(): array
    {
        return $this->schemaDefinitionPath;
    }

    public function getID(): string
    {
        return $this->id;
    }
}
