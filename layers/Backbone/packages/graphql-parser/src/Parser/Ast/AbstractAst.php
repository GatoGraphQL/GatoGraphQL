<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

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
