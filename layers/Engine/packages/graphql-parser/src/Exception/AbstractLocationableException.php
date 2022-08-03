<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Exception;

use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Exception\AbstractClientException;

abstract class AbstractLocationableException extends AbstractClientException
{
    public function __construct(
        private readonly FeedbackItemResolution $feedbackItemResolution,
        private readonly Location $location,
    ) {
        parent::__construct($feedbackItemResolution->getMessage());
    }

    public function getFeedbackItemResolution(): FeedbackItemResolution
    {
        return $this->feedbackItemResolution;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}
