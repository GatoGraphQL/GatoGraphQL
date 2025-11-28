<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;

abstract class AbstractFeedback implements FeedbackInterface
{
    /**
     * @param array<string,mixed> $extensions
     */
    public function __construct(
        protected FeedbackItemResolution $feedbackItemResolution,
        protected array $extensions = [],
    ) {
        // Merge the extensions from the feedback item resolution with the ones from the feedback
        $this->extensions = [
            ...$this->feedbackItemResolution->getExtensions(),
            ...$this->extensions,
        ];
    }

    public function getFeedbackItemResolution(): FeedbackItemResolution
    {
        return $this->feedbackItemResolution;
    }

    /**
     * @return array<string,mixed>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
