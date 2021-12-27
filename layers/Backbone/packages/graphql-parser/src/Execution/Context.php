<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

class Context
{
    private string $operationName;
    
    public function __construct(
        ?string $operationName = null,
        /** @var array<string, mixed> */
        private array $variableValues = [],
    ) {
        $this->operationName = $operationName ?? '';
    }

    public function getOperationName(): string
    {
        return $this->operationName;
    }

    /**
     * @return array<string, mixed>
     */
    public function getVariableValues(): array
    {
        return $this->variableValues;
    }
}
