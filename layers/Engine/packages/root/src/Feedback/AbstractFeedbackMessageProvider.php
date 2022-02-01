<?php

declare(strict_types=1);

namespace PoP\Root\Feedback;

abstract class AbstractFeedbackMessageProvider implements FeedbackMessageProviderInterface
{
    public function getSpecifiedByURL(string $code): ?string
    {
        return null;
    }
}
