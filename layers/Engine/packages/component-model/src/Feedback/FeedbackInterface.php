<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

interface FeedbackInterface
{
    public function getMessage(): string;
    public function getCode(): ?string;
    /**
     * @return array<string, mixed>
     */
    public function getData(): ?array;
}
