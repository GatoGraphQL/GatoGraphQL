<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;

interface OperationInterface extends LocatableInterface, WithDirectivesInterface, WithFieldsOrFragmentBondsInterface
{
    public function getName(): string;
    public function getOperationType(): string;

    /**
     * @return Variable[]
     */
    public function getVariables(): array;

    /**
     * @param Fragment[] $fragments
     * @return VariableReference[]
     */
    public function getVariableReferences(array $fragments): array;
}
