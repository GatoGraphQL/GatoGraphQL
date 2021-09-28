<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\GetterSetterObjects\BlockAttributes;

class PersistedQueryEndpointGraphiQLBlockAttributes
{
    protected string $query;
    protected array $variables;
    public function __construct(string $query, array $variables)
    {
        $this->query = $query;
        /** @var array<string, mixed> */
        $this->variables = $variables;
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
