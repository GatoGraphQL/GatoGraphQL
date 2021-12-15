<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Interfaces\LocatableInterface;
use PoP\GraphQLParser\Parser\Location;

abstract class AbstractAst implements LocatableInterface
{
    /** @var  Location */
    private $location;

    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }
}
