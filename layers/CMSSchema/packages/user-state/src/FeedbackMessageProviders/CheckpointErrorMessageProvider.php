<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\FeedbackMessageProviders;

use PoP\Root\FeedbackMessageProviders\AbstractFeedbackMessageProvider;
use PoP\Root\Feedback\FeedbackCategories;

class CheckpointErrorMessageProvider extends AbstractFeedbackMessageProvider
{
    public const E1 = '1';
    public const E2 = '2';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('The user is not logged-in', 'user-state'),
            self::E2 => $this->__('The user is logged-in', 'user-state'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
