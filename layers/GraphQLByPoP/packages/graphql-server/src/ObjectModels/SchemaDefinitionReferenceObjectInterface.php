<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface SchemaDefinitionReferenceObjectInterface
{
    public function getSchemaDefinitionPath(): array;

    public function getID(): string;
}
