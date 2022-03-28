<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractAst implements AstInterface, LocatableInterface
{
    public function __construct(protected Location $location)
    {
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    final public function __toString(): string
    {
        return $this->asQueryString();
    }
}
