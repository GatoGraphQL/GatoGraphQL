<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

interface QueryFeedbackInterface extends FeedbackInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array;
}
