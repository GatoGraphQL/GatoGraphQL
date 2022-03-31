<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

trait WithoutInnerElementsAstTrait
{
    final public function __toString(): string
    {
        return $this->asQueryString();
    }

    public abstract function asQueryString(): string;
}
