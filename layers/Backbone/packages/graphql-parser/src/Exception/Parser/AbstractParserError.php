<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Exception\Parser;

use Exception;
use PoPBackbone\GraphQLParser\Exception\LocationableExceptionInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

abstract class AbstractParserError extends Exception implements LocationableExceptionInterface
{
    public function __construct(
        string $message,
        private Location $location,
    ) {
        parent::__construct($message);
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}
