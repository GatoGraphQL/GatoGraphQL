<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Mutation;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\Query;
use PoPBackbone\GraphQLParser\Parser\ParsedData;

class Request implements RequestInterface
{
    /** @var OperationInterface[] */
    private array $operations = [];

    /** @var Fragment[] */
    private array $fragments = [];

    /** @var array<string, mixed> */
    private $variableValues = [];

    /** @var VariableReference[] */
    private array $variableReferences = [];

    // /** @var Variable[] */
    // private array $queryVariables = [];

    /** @var FragmentReference[] */
    private array $fragmentReferences = [];

    public function process(
        ParsedData|array $data,
        array $variableValues = [],
    ): self {
        if ($data instanceof ParsedData) {
            $data = $data->toArray();
        }

        if (array_key_exists('operations', $data)) {
            $this->addOperations($data['operations']);
        }

        if (array_key_exists('fragments', $data)) {
            $this->addFragments($data['fragments']);
        }

        if (array_key_exists('fragmentReferences', $data)) {
            $this->addFragmentReferences($data['fragmentReferences']);
        }

        // if (array_key_exists('variables', $data)) {
        //     $this->addQueryVariables($data['variables']);
        // }

        // if (array_key_exists('variableReferences', $data)) {
        //     /** @var VariableReference[] */
        //     $variableReferences = $data['variableReferences'];
        //     foreach ($variableReferences as $ref) {
        //         if (!array_key_exists($ref->getName(), $variableValues)) {
        //             $variable = $ref->getVariable();
        //             /**
        //              * If $variable is null, then it was not declared in the operation arguments
        //              * @see https://graphql.org/learn/queries/#variables
        //              */
        //             if ($variable === null) {
        //                 throw new InvalidRequestException(
        //                     $this->getVariableHasntBeenDeclaredErrorMessage($ref->getName()),
        //                     $ref->getLocation()
        //                 );
        //             }
        //             if ($variable->hasDefaultValue()) {
        //                 $variableValues[$variable->getName()] = $variable->getDefaultValue()->getValue();
        //                 continue;
        //             }
        //             throw new InvalidRequestException(
        //                 $this->getVariableHasntBeenSubmittedErrorMessage($ref->getName()),
        //                 $ref->getLocation()
        //             );
        //         }
        //     }

        //     $this->addVariableReferences($variableReferences);
        // }

        // $this->setVariableValues($variableValues);

        return $this;
    }

    protected function getVariableHasntBeenDeclaredErrorMessage(string $variableName): string
    {
        return \sprintf('Variable \'%s\' hasn\'t been declared', $variableName);
    }

    protected function getVariableHasntBeenSubmittedErrorMessage(string $variableName): string
    {
        return \sprintf('Variable \'%s\' hasn\'t been submitted', $variableName);
    }

    /**
     * @param OperationInterface[] $operations
     */
    public function addOperations(array $operations): void
    {
        $this->operations = array_merge(
            $this->operations,
            $operations
        );
    }

    /**
     * @param Variable[] $queryVariables
     */
    public function addQueryVariables(array $queryVariables): void
    {
        foreach ($queryVariables as $queryVariable) {
            $this->queryVariables[] = $queryVariable;
        }
    }

    /**
     * @param VariableReference[] $variableReferences
     */
    public function addVariableReferences(array $variableReferences): void
    {
        foreach ($variableReferences as $variableReference) {
            $this->variableReferences[] = $variableReference;
        }
    }

    /**
     * @param FragmentReference[] $fragmentReferences
     */
    public function addFragmentReferences(array $fragmentReferences): void
    {
        foreach ($fragmentReferences as $fragmentReference) {
            $this->fragmentReferences[] = $fragmentReference;
        }
    }

    /**
     * @param Fragment[] $fragments
     */
    public function addFragments(array $fragments): void
    {
        foreach ($fragments as $fragment) {
            $this->addFragment($fragment);
        }
    }

    /**
     * @return OperationInterface[]
     */
    public function getOperations(): array
    {
        return $this->operations;
    }

    public function hasOperations(): bool
    {
        return count($this->operations) > 0;
    }

    /**
     * @return Fragment[]
     */
    public function getFragments(): array
    {
        return $this->fragments;
    }

    public function addFragment(Fragment $fragment): void
    {
        $this->fragments[] = $fragment;
    }

    public function getFragment(string $name): ?Fragment
    {
        foreach ($this->fragments as $fragment) {
            if ($fragment->getName() === $name) {
                return $fragment;
            }
        }

        return null;
    }

    public function hasFragments(): bool
    {
        return count($this->fragments) > 0;
    }

    /**
     * @return array<string,mixed>
     */
    public function getVariableValues(): array
    {
        return $this->variableValues;
    }

    public function setVariableValues(array $variableValues): void
    {
        $this->variableValues = $variableValues;
        foreach ($this->variableReferences as $reference) {
            /** invalid request with no variable */
            if (!$reference->getVariable()) {
                continue;
            }
            $variableName = $reference->getVariable()->getName();

            /** no variable was set at the time */
            if (!array_key_exists($variableName, $variableValues)) {
                continue;
            }

            $reference->getVariable()->setValue($variableValues[$variableName]);
            $reference->setValue($variableValues[$variableName]);
        }
    }

    public function getVariableValue(string $name): mixed
    {
        return $this->variableValues[$name] ?? null;
    }

    public function hasVariable(string $name): bool
    {
        return array_key_exists($name, $this->variableValues);
    }

    /**
     * @return Variable[]
     */
    public function getQueryVariables(): array
    {
        return $this->queryVariables;
    }

    /**
     * @param Variable[] $queryVariables
     */
    public function setQueryVariables(array $queryVariables): void
    {
        $this->queryVariables = $queryVariables;
    }

    /**
     * @return FragmentReference[]
     */
    public function getFragmentReferences(): array
    {
        return $this->fragmentReferences;
    }

    /**
     * @param FragmentReference[] $fragmentReferences
     */
    public function setFragmentReferences(array $fragmentReferences): void
    {
        $this->fragmentReferences = $fragmentReferences;
    }

    /**
     * @return VariableReference[]
     */
    public function getVariableReferences(): array
    {
        return $this->variableReferences;
    }
}
