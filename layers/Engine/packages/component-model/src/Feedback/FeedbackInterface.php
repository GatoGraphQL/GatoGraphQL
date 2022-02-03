<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;

interface FeedbackInterface
{
    public function getMessage(): string;
    public function getCode(): ?string;
    public function getLocation(): Location;
    /**
     * @return array<string, mixed>
     */
    public function getData(): ?array;
}
