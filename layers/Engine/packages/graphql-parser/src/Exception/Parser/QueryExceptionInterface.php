<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;

interface QueryExceptionInterface
{
    public function getAstNode(): AstInterface;
}
