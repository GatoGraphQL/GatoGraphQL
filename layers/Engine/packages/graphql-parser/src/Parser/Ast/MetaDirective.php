<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Directive as ExtendedDirective;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Location;

class MetaDirective extends ExtendedDirective
{
    /**
     * @param Directive[] $nestedDirectives
     * @param Argument[] $arguments
     */
    public function __construct(
        string $name,
        array $arguments = [],
        protected array $nestedDirectives = [],
        ?Location $location = null,
    ) {
        parent::__construct(
            $name,
            $arguments,
            $location,
        );
        $this->setNestedDirectives($nestedDirectives);
    }

    public function hasNestedDirectives(): bool
    {
        return count($this->nestedDirectives) > 0;
    }

    /**
     * @return Directive[]
     */
    public function getNestedDirectives(): array
    {
        return $this->nestedDirectives;
    }

    /**
     * @param Directive[] $nestedDirectives
     */
    public function setNestedDirectives(array $nestedDirectives): void
    {
        $this->nestedDirectives = $nestedDirectives;
    }

    public function addNestedDirective(Directive $nestedDirective): void
    {
        $this->nestedDirectives[] = $nestedDirective;
    }
}
