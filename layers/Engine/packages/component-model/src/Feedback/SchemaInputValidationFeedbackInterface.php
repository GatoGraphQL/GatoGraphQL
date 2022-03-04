<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

interface SchemaInputValidationFeedbackInterface extends QueryFeedbackInterface
{
    /**
     * @return SchemaInputValidationFeedbackInterface[]
     */
    public function getNested(): array;
}
