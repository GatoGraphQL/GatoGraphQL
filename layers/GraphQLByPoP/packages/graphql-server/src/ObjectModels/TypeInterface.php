<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface TypeInterface extends WrappingTypeOrSchemaDefinitionReferenceObjectInterface
{
    public function getKind(): string;

    public function getName(): string;

    public function getDescription(): ?string;
}
