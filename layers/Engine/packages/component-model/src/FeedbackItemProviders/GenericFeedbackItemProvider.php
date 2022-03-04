<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\ComponentModel\Feedback\FeedbackCategories;
use PoP\Root\FeedbackItemProviders\GenericFeedbackItemProvider as UpstreamGenericFeedbackItemProvider;

class GenericFeedbackItemProvider extends UpstreamGenericFeedbackItemProvider
{
    public const W1 = 'w1';
    public const N1 = 'n1';
    public const S1 = 's1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::W1,
            self::N1,
            self::S1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::W1,
            self::N1,
            self::S1
                => '%s',
            default
                => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::W1 => FeedbackCategories::WARNING,
            self::N1 => FeedbackCategories::NOTICE,
            self::S1 => FeedbackCategories::SUGGESTION,
            default => parent::getCategory($code),
        };
    }
}
