<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

abstract class AbstractFeedback implements FeedbackInterface
{
    public function __construct(
        protected FeedbackResolution $feedbackResolution,
        /** @var array<string, mixed> */
        protected array $data = [],
    ) {
    }

    public function getFeedbackResolution(): FeedbackResolution
    {
        return $this->feedbackResolution;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
