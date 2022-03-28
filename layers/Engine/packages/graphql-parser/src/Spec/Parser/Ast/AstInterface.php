<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface AstInterface
{
    public function asQueryString(): string;
}
