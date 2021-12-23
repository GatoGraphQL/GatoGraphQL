<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;

class ParsedData
{
    public function __construct(
        /** @var OperationInterface[] */
        private array $operations,
        /** @var Fragment[] */
        private array $fragments,
        /** @var FragmentReference[] */
        private array $fragmentReferences,
        /** @var Variable[] */
        private array $variables,
        /** @var VariableReference[] */
        private array $variableReferences,
    ) {
    }

    /**
     * @return OperationInterface[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    /**
     * @return Fragment[]
     */
    public function getFragments(): array
    {
        return $this->fragments;
    }

    /**
     * @return FragmentReference[]
     */
    public function getFragmentReferences(): array
    {
        return $this->fragmentReferences;
    }

    /**
     * @return Variable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @return VariableReference[]
     */
    public function getVariableReferences(): array
    {
        return $this->variableReferences;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'operations'         => $this->getOperations(),
            'fragments'          => $this->getFragments(),
            'fragmentReferences' => $this->getFragmentReferences(),
            'variables'          => $this->getVariables(),
            'variableReferences' => $this->getVariableReferences(),
        ];
    }
}
