<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;

interface DocumentInterface
{
/**
     * @return OperationInterface[]
     */
    public function getOperations(): array;
    /**
     * @return Fragment[]
     */
    public function getFragments(): array;
    public function getFragment(string $name): ?Fragment;
    public function validate(): void;
    /**
     * Gather all the VariableReference within the Operation.
     *
     * @return VariableReference[]
     */
    public function getVariableReferencesInOperation(OperationInterface $operation): array;
    public function asDocumentString(): string;
    /**
     * Create a dictionary mapping every element of the AST
     * to their parent. This is useful to report the full
     * path to an AST node in the query when displaying errors.
     *
     * @return SplObjectStorage<AstInterface,AstInterface>
     */
    public function getASTNodeAncestors(): SplObjectStorage;
}
