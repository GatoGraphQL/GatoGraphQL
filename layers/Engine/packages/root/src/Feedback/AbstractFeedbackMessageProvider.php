<?php

declare(strict_types=1);

namespace PoP\Root\Feedback;

use PoP\Root\Helpers\ClassHelpers;

abstract class AbstractFeedbackMessageProvider implements FeedbackMessageProviderInterface
{
    public function getNamespace(): string
    {
        return ClassHelpers::getClassPSR4Namespace(\get_called_class());
    }
    
    public function getSpecifiedByURL(string $code): ?string
    {
        return null;
    }
}
