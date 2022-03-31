<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithInnerElementsAstInterface extends AstInterface
{
    public function asQueryString(bool $includeInnerElements = false): string;
}
