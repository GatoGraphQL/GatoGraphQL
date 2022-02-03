<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

interface ObjectFeedbackInterface extends SchemaFeedbackInterface
{
    public function getObjectIDs(): array;
}
