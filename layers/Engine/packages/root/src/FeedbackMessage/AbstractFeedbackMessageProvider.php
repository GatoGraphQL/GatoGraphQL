<?php

declare(strict_types=1);

namespace PoP\Root\FeedbackMessage;

use Exception;
use PoP\Root\FeedbackMessage\FeedbackMessageCategories;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractFeedbackMessageProvider implements FeedbackMessageProviderInterface
{
    use BasicServiceTrait;

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
        return \str_pad((string) $code, 4, "0", \STR_PAD_LEFT);
    }

    final public function getMessage(int $code, string|int|float|bool ...$args): string
    {
        return \sprintf(
            $this->getMessagePlaceholder($code),
            ...$args
        );
    }

    public function getMessagePlaceholder(int $code): string
    {
        throw new Exception(
            $this->__('There is no message placeholder for feedback message with code \'%s\'', $code)
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
