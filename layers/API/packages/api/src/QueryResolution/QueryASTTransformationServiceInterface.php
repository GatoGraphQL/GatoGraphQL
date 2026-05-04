<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;

interface QueryASTTransformationServiceInterface
{
    /**
     * Build the operation→fields map that the engine will execute.
     * Subclasses can inject extra fields (eg: the GraphQL SuperRoot
     * wrapper) per operation. Multiple Query Execution ordering is
     * enforced by the engine's per-operation drain loop, not by AST
     * rewrites here.
     *
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForExecution(
        Document $document,
        array $operations,
        array $fragments,
    ): SplObjectStorage;
}
