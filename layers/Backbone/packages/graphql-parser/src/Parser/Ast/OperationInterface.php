<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;

interface OperationInterface extends LocatableInterface, WithDirectivesInterface
{
    public function getName(): string;

    /**
     * @return Variable[]
     */
    public function getVariables(): array;
}
