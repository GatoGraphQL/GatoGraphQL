<?php

declare(strict_types=1);

namespace PoP\Root\Feedback;

use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Registries\FeedbackMessageCategories;

abstract class AbstractFeedbackMessageProvider implements FeedbackMessageProviderInterface
{
    public function getNamespace(): string
    {
        return ClassHelpers::getClassPSR4Namespace(\get_called_class());
    }
    
    public function getCategory(string $code): string
    {
        return FeedbackMessageCategories::ERROR;
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return null;
    }
}
