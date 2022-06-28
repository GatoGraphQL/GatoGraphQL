<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface AstInterface extends LocatableInterface
{
    public function asQueryString(): string;
}
