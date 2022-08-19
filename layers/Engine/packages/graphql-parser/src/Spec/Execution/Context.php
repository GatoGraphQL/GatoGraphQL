<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

class Context
{
    private readonly string $operationName;

    /**
     * @param array<string,mixed> $variableValues
     */
    public function __construct(
        ?string $operationName = null,
        private readonly array $variableValues = [],
    ) {
        $this->operationName = $operationName !== null ? trim($operationName) : '';
    }

    public function getOperationName(): string
    {
        return $this->operationName;
    }

    /**
     * @return array<string,mixed>
     */
    public function getVariableValues(): array
    {
        return $this->variableValues;
    }

    public function hasVariableValue(string $variableName): bool
    {
        return array_key_exists($variableName, $this->variableValues);
    }

    public function getVariableValue(string $variableName): mixed
    {
        return $this->variableValues[$variableName] ?? null;
    }
}
