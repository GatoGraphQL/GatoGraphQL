<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface SchemaDefinitionReferenceObjectInterface
{
    /**
     * By default, types are static
     */
    public function isDynamicType(): bool;

    public function getSchemaDefinition(): array;

    public function getSchemaDefinitionPath(): array;

    public function getID(): string;
}
