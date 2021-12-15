<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Interfaces\LocatableInterface;
use PoP\GraphQLParser\Parser\Location;

abstract class AbstractAst implements LocatableInterface
{
    public function __construct(private Location $location)
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
}
