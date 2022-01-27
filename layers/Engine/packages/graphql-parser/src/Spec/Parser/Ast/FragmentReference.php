<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class FragmentReference extends AbstractAst implements FragmentBondInterface
{
    public function __construct(
        protected string $name,
        Location $location,
    ) {
        parent::__construct($location);
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
