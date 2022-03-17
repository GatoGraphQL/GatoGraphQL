<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Directive extends AbstractAst
{
    use WithArgumentsTrait;

    /**
     * @param Argument[] $arguments
     */
    public function __construct(
        protected $name,
        array $arguments,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setArguments($arguments);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
