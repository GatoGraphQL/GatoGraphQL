<?php

declare(strict_types=1);

namespace PoPAPI\API\FeedbackMessageProviders;

use PoP\Root\FeedbackMessageProviders\AbstractFeedbackMessageProvider;
use PoP\Root\FeedbackMessage\FeedbackMessageCategories;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
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
            self::E1 => $this->__('The query in the body is empty', 'api'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1 => FeedbackMessageCategories::ERROR,
            default => parent::getCategory($code),
        };
    }
}
