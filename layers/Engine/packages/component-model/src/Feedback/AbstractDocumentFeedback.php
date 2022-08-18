<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;

/**
 * Error that concern the GraphQL document. The `$location` is where the error happens.
 */
abstract class AbstractDocumentFeedback extends AbstractFeedback implements DocumentFeedbackInterface
{
    /**
     * @param array<string,mixed> $extensions
     */
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        protected Location $location,
        /** @var array<string,mixed> */
        array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
            $extensions,
        );
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}
