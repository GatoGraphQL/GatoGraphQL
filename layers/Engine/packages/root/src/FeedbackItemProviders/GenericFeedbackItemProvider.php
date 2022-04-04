<?php

declare(strict_types=1);

namespace PoP\Root\FeedbackItemProviders;

use PoP\Root\Feedback\FeedbackCategories;

class GenericFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const D1 = 'd1';
    public final const L1 = 'l1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::D1,
            self::L1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1,
            self::D1,
            self::L1
                => '%s',
            default
                => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1 => FeedbackCategories::ERROR,
            self::D1 => FeedbackCategories::DEPRECATION,
            self::L1 => FeedbackCategories::LOG,
            default => parent::getCategory($code),
        };
    }
}
