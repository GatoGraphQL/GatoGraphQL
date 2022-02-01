<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\FeedbackMessage;

use PoP\Root\FeedbackMessage\AbstractFeedbackMessageProvider;
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
        return match($code) {
            self::E1 => $this->__('Before executing `%s`, must call `validateAndInitialize`', 'graphql-server'),
            default => parent::getMessagePlaceholder($code),
        };
    }
    
    public function getCategory(string $code): string
    {
        return match($code) {
            self::E1 => FeedbackMessageCategories::ERROR,
            default => parent::getCategory($code),
        };
    }
}
