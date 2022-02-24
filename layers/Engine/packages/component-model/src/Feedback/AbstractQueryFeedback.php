<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractQueryFeedback extends AbstractFeedback implements QueryFeedbackInterface
{
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        protected Location $location,
        /** @var array<string, mixed> */
        protected array $extensions = [],
    ) {
        parent::__construct(
            $feedbackItemResolution,
        );
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
