<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithAstValueInterface
{
    public function getAstValue(): mixed;
}
