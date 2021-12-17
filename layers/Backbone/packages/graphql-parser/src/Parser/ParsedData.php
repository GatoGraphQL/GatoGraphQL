<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Mutation;
use PoPBackbone\GraphQLParser\Parser\Ast\Query;

class ParsedData
{
    public function __construct(
        private array $queryOperations,
        private array $mutationOperations,
        /** @var Query[] */
        private array $queries,
        /** @var Mutation[] */
        private array $mutations,
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

    public function getQueryOperations(): array
    {
        return $this->queryOperations;
    }

    public function getMutationOperations(): array
    {
        return $this->mutationOperations;
    }

    /**
     * @return Query[]
     */
    public function getQueries(): array
    {
        return $this->queries;
    }

    /**
     * @return Mutation[]
     */
    public function getMutations(): array
    {
        return $this->mutations;
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
}
