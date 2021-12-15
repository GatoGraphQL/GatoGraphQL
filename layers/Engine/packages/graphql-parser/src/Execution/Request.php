<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Execution;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoP\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Parser\Ast\Fragment;
use PoP\GraphQLParser\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Parser\Ast\Mutation;
use PoP\GraphQLParser\Parser\Ast\Query;

class Request
{
    /** @var Query[] */
    private array $queries = [];

    /** @var Fragment[] */
    private array $fragments = [];

    /** @var Mutation[] */
    private array $mutations = [];

    /** @var array<string, mixed> */
    private $variables = [];

    /** @var VariableReference[] */
    private array $variableReferences = [];

    /** @var Variable[] */
    private array $queryVariables = [];

    /** @var FragmentReference[] */
    private array $fragmentReferences = [];

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $variables
     */
    public function __construct(array $data = [], array $variables = [])
    {
        if (array_key_exists('queries', $data)) {
            $this->addQueries($data['queries']);
        }

        if (array_key_exists('mutations', $data)) {
            $this->addMutations($data['mutations']);
        }

        if (array_key_exists('fragments', $data)) {
            $this->addFragments($data['fragments']);
        }

        if (array_key_exists('fragmentReferences', $data)) {
            $this->addFragmentReferences($data['fragmentReferences']);
        }

        if (array_key_exists('variables', $data)) {
            $this->addQueryVariables($data['variables']);
        }

        if (array_key_exists('variableReferences', $data)) {
            /** @var VariableReference[] */
            $variableReferences = $data['variableReferences'];
            foreach ($variableReferences as $ref) {
                if (!array_key_exists($ref->getName(), $variables)) {
                    $variable = $ref->getVariable();
                    /**
                     * If $variable is null, then it was not declared in the operation arguments
                     * @see https://graphql.org/learn/queries/#variables
                     */
                    if ($variable === null) {
                        throw new InvalidRequestException(sprintf("Variable %s hasn't been declared", $ref->getName()), $ref->getLocation());
                    }
                    if ($variable->hasDefaultValue()) {
                        $variables[$variable->getName()] = $variable->getDefaultValue()->getValue();
                        continue;
                    }
                    throw new InvalidRequestException(sprintf("Variable %s hasn't been submitted", $ref->getName()), $ref->getLocation());
                }
            }

            $this->addVariableReferences($variableReferences);
        }

        $this->setVariables($variables);
    }

    /**
     * @param Query[] $queries
     */
    public function addQueries(array $queries): void
    {
        foreach ($queries as $query) {
            $this->queries[] = $query;
        }
    }

    /**
     * @param Mutation[] $mutations
     */
    public function addMutations(array $mutations): void
    {
        foreach ($mutations as $mutation) {
            $this->mutations[] = $mutation;
        }
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
     * @return Query[]
     */
    public function getAllOperations(): array
    {
        return array_merge($this->mutations, $this->queries);
    }

    /**
     * @return Query[]
     */
    public function getQueries(): array
    {
        return $this->queries;
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

    /**
     * @return Mutation[]
     */
    public function getMutations(): array
    {
        return $this->mutations;
    }

    public function hasQueries(): bool
    {
        return count($this->queries) > 0;
    }

    public function hasMutations(): bool
    {
        return count($this->mutations) > 0;
    }

    public function hasFragments(): bool
    {
        return count($this->fragments) > 0;
    }

    /**
     * @return Variable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
        foreach ($this->variableReferences as $reference) {
            /** invalid request with no variable */
            if (!$reference->getVariable()) {
                continue;
            }
            $variableName = $reference->getVariable()->getName();

            /** no variable was set at the time */
            if (!array_key_exists($variableName, $variables)) {
                continue;
            }

            $reference->getVariable()->setValue($variables[$variableName]);
            $reference->setValue($variables[$variableName]);
        }
    }

    public function getVariable(string $name): ?Variable
    {
        return $this->hasVariable($name) ? $this->variables[$name] : null;
    }

    public function hasVariable(string $name): bool
    {
        return array_key_exists($name, $this->variables);
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
