<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithDirectivesInterface
{
    /**
     * @return Directive[]
     */
    public function getDirectives(): array;

    public function hasDirectives(): bool;
}
