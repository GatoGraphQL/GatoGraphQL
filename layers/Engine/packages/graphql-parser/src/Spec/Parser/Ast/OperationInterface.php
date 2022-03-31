<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface OperationInterface extends AstInterface, LocatableInterface, WithDirectivesInterface, WithFieldsOrFragmentBondsInterface
{
    public function getName(): string;
    public function getOperationType(): string;

    /**
     * @return Variable[]
     */
    public function getVariables(): array;
}
