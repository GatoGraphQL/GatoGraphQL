<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

interface QueryAugmenterServiceInterface
{
    public function isExecutingAllOperations(string $operationName): bool;
}
