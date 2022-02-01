<?php

declare(strict_types=1);

namespace PoP\Root\FeedbackMessage;

use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Registries\FeedbackMessageCategories;

abstract class AbstractFeedbackMessageProvider implements FeedbackMessageProviderInterface
{
    final public function getNamespacedCode(string $code): string
    {
        return $$this->getNamespace() . $code;
    }

    protected function getNamespace(): string
    {
        return ClassHelpers::getClassPSR4Namespace(\get_called_class());
    }

    final public function getMessage(string $code, string|int|float|bool ...$args): string
    {
        return \sprintf($code, ...$args);
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
