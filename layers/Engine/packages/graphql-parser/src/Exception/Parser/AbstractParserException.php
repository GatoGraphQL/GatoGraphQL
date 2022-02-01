<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use Exception;
use PoP\GraphQLParser\Exception\LocationableExceptionInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractParserException extends Exception implements LocationableExceptionInterface
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
