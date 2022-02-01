<?php

declare(strict_types=1);

namespace PoP\Root\FeedbackMessage;

use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\FeedbackMessage\FeedbackMessageCategories;

abstract class AbstractFeedbackMessageProvider implements FeedbackMessageProviderInterface
{
    final public function getNamespacedCode(int $code): string
    {
        return $$this->getNamespace() . $this->getCodeToStr($code);
    }

    protected function getNamespace(): string
    {
        return ClassHelpers::getClassPSR4Namespace(\get_called_class());
    }

    protected function getCodeToStr(int $code): string
    {
        return (string) $code;
    }

    final public function getMessage(int $code, string|int|float|bool ...$args): string
    {
        return \sprintf(
            $this->getMessagePlaceholder($code),
            ...$args
        );
    }
    
    public function getCategory(int $code): string
    {
        return FeedbackMessageCategories::ERROR;
    }

    public function getSpecifiedByURL(int $code): ?string
    {
        return null;
    }
}
