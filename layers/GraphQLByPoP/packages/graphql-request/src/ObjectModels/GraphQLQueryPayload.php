<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\ObjectModels;

class GraphQLQueryPayload
{
    public function __construct(
        public readonly ?string $query,
        /**
         * @var array<string,mixed>|null
         */
        public readonly ?array $variables,
        public readonly ?string $operationName,
    ) {
    }
}
