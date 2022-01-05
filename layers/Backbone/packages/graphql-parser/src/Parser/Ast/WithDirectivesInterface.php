<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

interface WithDirectivesInterface
{
    /**
     * @param Directive[] $directives
     */
    public function setDirectives(array $directives): void;

    /**
     * @return Directive[]
     */
    public function getDirectives(): array;

    public function hasDirectives(): bool;
}
