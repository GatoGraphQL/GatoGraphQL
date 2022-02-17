<?php

declare(strict_types=1);

namespace PoP\Engine\FeedbackMessageProviders;

use PoP\Root\FeedbackMessageProviders\AbstractFeedbackMessageProvider;
use PoP\Root\Feedback\FeedbackCategories;

class CheckpointErrorMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E1 = '1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('REQUEST_METHOD is not \'POST\'', 'engine'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
