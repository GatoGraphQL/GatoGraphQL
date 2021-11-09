<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\AppObjects\BlockAttributes;

class PersistedQueryEndpointGraphiQLBlockAttributes
{
    public function __construct(
        protected string $query,
        /** @var array<string, mixed> */
        protected array $variables,
    ) {
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    /** @return array<string, mixed> */
    public function getVariables(): array
    {
        return $this->variables;
    }
}
