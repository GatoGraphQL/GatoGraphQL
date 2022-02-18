<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception\Parser;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\LocationableExceptionInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\AbstractClientException;

abstract class AbstractParserException extends AbstractClientException implements LocationableExceptionInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        private Location $location,
    ) {
        parent::__construct($feedbackItemResolution->getMessage());
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}
