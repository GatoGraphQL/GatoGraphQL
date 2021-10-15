<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasPossibleTypesTypeInterface extends TypeInterface
{
    public function getPossibleTypes(): array;
    public function getPossibleTypeIDs(): array;
}
