<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

abstract class AbstractFeedback implements FeedbackInterface
{
    public function __construct(
        protected FeedbackItemResolution $feedbackItemResolution,
        /** @var array<string, mixed> */
        protected array $data = [],
    ) {
    }

    public function getFeedbackItemResolution(): FeedbackItemResolution
    {
        return $this->feedbackItemResolution;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
