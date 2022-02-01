<?php

declare(strict_types=1);

namespace PoP\Root\Feedback;

class FeedbackMessageManager implements FeedbackMessageManagerInterface
{
    public function getFeedbackMessage(string $code, string|int|float|bool ...$args): string
    {
        return '';
    }
}
