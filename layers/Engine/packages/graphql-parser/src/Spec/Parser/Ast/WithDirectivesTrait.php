<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

trait WithDirectivesTrait
{
    /** @var Directive[] */
    protected array $directives;

    public function hasDirectives(): bool
    {
        return count($this->directives) > 0;
    }

    /**
     * @return Directive[]
     */
    public function getDirectives(): array
    {
        return $this->directives;
    }

    /**
     * @param Directive[] $directives
     */
    public function setDirectives(array $directives): void
    {
        $this->directives = $directives;
    }
}
