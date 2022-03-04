<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

interface ObjectTypeFieldResolutionFeedbackInterface extends QueryFeedbackInterface
{
    /**
     * @return ObjectTypeFieldResolutionFeedbackInterface[]
     */
    public function getNested(): array;
}
