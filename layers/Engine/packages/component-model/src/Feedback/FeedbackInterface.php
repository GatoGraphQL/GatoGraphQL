<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;

interface FeedbackInterface
{
    public function getFeedbackItemResolution(): FeedbackItemResolution;
    /**
     * @return array<string,mixed>
     */
    public function getExtensions(): array;
}
