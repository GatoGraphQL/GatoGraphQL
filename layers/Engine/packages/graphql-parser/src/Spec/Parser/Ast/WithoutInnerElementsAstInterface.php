<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithoutInnerElementsAstInterface extends AstInterface
{
    public function asQueryString(): string;
}
