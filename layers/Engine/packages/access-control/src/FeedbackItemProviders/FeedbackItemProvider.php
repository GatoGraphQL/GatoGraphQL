<?php

declare(strict_types=1);

namespace PoP\AccessControl\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';

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
            self::E1 => $this->__('Access to directives in field(s) \'%s\' has been disabled', 'access-control'),
            self::E2 => $this->__('Access to field(s) \'%s\' has been disabled', 'access-control'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1,
            self::E2
                => FeedbackCategories::ERROR,
            default
                => parent::getCategory($code),
        };
    }
}
