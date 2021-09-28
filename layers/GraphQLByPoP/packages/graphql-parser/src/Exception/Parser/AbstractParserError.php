<?php

/**
 * Date: 16.11.16
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Exception\Parser;

use Exception;
use GraphQLByPoP\GraphQLParser\Exception\Interfaces\LocationableExceptionInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;

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
