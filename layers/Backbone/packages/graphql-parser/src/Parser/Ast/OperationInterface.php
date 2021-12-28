<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;

interface OperationInterface extends LocatableInterface, WithDirectivesInterface, WithFieldsOrFragmentBondsInterface
{
    public function getName(): string;
    public function getOperationType(): string;

    /**
     * @param Fragment[] $fragments
     * @return FragmentReference[]
     */
    public function getFragmentReferences(array $fragments): array;

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
