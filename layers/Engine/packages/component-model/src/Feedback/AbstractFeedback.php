<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractFeedback implements FeedbackInterface
{
    public function __construct(
        protected FeedbackItemResolution $feedbackItemResolution,
        /** @var array<string, mixed> */
        protected array $extensions = [],
    ) {
    }

    public function getFeedbackItemResolution(): FeedbackItemResolution
    {
        return $this->feedbackItemResolution;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
