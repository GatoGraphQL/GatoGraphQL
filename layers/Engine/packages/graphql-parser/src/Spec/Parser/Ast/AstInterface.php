<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface AstInterface
{
    /**
     * ID to uniquely identify the AST element
     * from all other elements in the GraphQL query.
     */
    public function getID(): string;
}
