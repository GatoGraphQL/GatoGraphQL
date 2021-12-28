<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

interface WithAstValueInterface
{
    public function getAstValue(): mixed;
}
