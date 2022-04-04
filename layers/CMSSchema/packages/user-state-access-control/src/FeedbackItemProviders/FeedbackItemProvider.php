<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateAccessControl\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
            self::E3,
            self::E4,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to access directives in field(s) \'%1$s\' for type \'%2$s\'', 'user-state-access-control'),
            self::E2 => $this->__('You must be logged in to access field(s) \'%1$s\' for type \'%2$s\'', 'user-state-access-control'),
            self::E3 => $this->__('You must not be logged in to access directives in field(s) \'%1$s\' for type \'%2$s\'', 'user-state-access-control'),
            self::E4 => $this->__('You must not be logged in to access field(s) \'%1$s\' for type \'%2$s\'', 'user-state-access-control'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1,
            self::E2,
            self::E3,
            self::E4
                => FeedbackCategories::ERROR,
            default
                => parent::getCategory($code),
        };
    }
}
