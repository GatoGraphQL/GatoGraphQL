<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Exception\Parser;

use Exception;
use PoP\GraphQLParser\Exception\Interfaces\LocationableExceptionInterface;
use PoP\GraphQLParser\Parser\Location;

abstract class AbstractParserError extends Exception implements LocationableExceptionInterface
{

    /** @var Location */
    private $location;

    public function __construct($message, Location $location)
    {
        parent::__construct($message);

        $this->location = $location;
    }

    public function getLocation()
    {
        return $this->location;
    }
}
