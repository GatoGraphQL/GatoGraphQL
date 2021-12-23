<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;

interface OperationInterface extends LocatableInterface, WithDirectivesInterface
{
    public function getName(): string;
    public function getOperationType(): string;

    /**
     * @return FragmentReference[]
     */
    public function getFragmentReferences(): array;

    /**
     * @return Variable[]
     */
    public function getVariables(): array;
    
    /**
     * @return VariableReference[]
     */
    public function getVariableReferences(): array;
}
