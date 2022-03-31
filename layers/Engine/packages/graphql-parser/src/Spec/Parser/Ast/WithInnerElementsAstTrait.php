<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

trait WithInnerElementsAstTrait
{
    final public function __toString(): string
    {
        return $this->asQueryString(true);
    }

    public abstract function asQueryString(bool $includeInnerElements = false): string;
}
