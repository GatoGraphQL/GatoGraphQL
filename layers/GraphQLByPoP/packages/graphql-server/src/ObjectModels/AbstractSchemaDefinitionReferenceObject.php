<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

abstract class AbstractSchemaDefinitionReferenceObject implements SchemaDefinitionReferenceObjectInterface
{
    protected string $id;

    public function getID(): string
    {
        return $this->id;
    }
}
