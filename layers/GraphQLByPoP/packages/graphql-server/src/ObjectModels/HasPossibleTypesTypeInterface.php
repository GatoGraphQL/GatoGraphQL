<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasPossibleTypesTypeInterface extends NamedTypeInterface
{
    public function getPossibleTypeIDs(): array;
}
