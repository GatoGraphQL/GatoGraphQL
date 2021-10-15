<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface TypeInterface extends SchemaDefinitionReferenceObjectInterface
{
    public function getKind(): string;

    public function getName(): string;
}
