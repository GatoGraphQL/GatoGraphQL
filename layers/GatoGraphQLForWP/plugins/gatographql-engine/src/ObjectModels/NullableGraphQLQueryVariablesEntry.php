<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ObjectModels;

class NullableGraphQLQueryVariablesEntry
{
    /**
     * @param array<string,mixed> $variables
     */
    public function __construct(
        public readonly ?string $query,
        public readonly ?array $variables,
    ) {
    }
}
