<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;

interface DocumentInterface
{
    public function getOperations(): array;
    public function getFragments(): array;
    public function getFragment(string $name): ?Fragment;
    public function validate(): void;
    /**
     * Gather all the VariableReference within the Operation.
     *
     * @return VariableReference[]
     */
    public function getVariableReferencesInOperation(OperationInterface $operation): array;
}
