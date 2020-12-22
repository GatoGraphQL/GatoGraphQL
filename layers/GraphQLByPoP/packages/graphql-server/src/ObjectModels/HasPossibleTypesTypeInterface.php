<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

interface HasPossibleTypesTypeInterface
{
    public function getPossibleTypes(): array;
    public function getPossibleTypeIDs(): array;
}
