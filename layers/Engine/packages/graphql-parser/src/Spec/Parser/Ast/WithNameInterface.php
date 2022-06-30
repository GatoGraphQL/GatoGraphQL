<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithNameInterface extends AstInterface
{
    public function getName(): string;
}
