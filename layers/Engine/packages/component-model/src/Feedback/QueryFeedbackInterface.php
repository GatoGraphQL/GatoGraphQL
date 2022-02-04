<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;

interface QueryFeedbackInterface extends FeedbackInterface
{
    public function getLocation(): Location;
    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array;
}
