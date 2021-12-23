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
use PoPBackbone\GraphQLParser\Parser\Ast\Document;

interface RequestInterface
{
    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $variableValues
     * @throws InvalidRequestException
     */
    public function process(
        Document|array $data,
        array $variableValues = [],
    ): self;

    /**
     * @param OperationInterface[] $operations
     */
    public function addOperations(array $operations): void;
    /**
     * @param Variable[] $queryVariables
     */
    public function addQueryVariables(array $queryVariables): void;
    /**
     * @param VariableReference[] $variableReferences
     */
    public function addVariableReferences(array $variableReferences): void;
    /**
     * @param FragmentReference[] $fragmentReferences
     */
    public function addFragmentReferences(array $fragmentReferences): void;
    /**
     * @param Fragment[] $fragments
     */
    public function addFragments(array $fragments): void;
    /**
     * @return OperationInterface[]
     */
    public function getOperations(): array;
    /**
     * @return Fragment[]
     */
    public function getFragments(): array;
    public function addFragment(Fragment $fragment): void;
    public function getFragment(string $name): ?Fragment;
    public function hasOperations(): bool;
    public function hasFragments(): bool;
    /**
     * @return array<string,mixed>
     */
    public function getVariableValues(): array;
    public function setVariableValues(array $variableValues): void;
    public function getVariableValue(string $name): mixed;
    public function hasVariable(string $name): bool;
    /**
     * @return Variable[]
     */
    public function getQueryVariables(): array;
    /**
     * @param Variable[] $queryVariables
     */
    public function setQueryVariables(array $queryVariables): void;
    /**
     * @return FragmentReference[]
     */
    public function getFragmentReferences(): array;
    /**
     * @param FragmentReference[] $fragmentReferences
     */
    public function setFragmentReferences(array $fragmentReferences): void;
    /**
     * @return VariableReference[]
     */
    public function getVariableReferences(): array;
}
