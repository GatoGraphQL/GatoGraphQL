<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

interface FeedbackInterface
{
    public function getFeedbackItemResolution(): FeedbackItemResolution;
    /**
     * @return array<string, mixed>
     */
    public function getData(): ?array;
}
