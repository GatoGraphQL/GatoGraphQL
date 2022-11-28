<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserStateMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E6 = 'e6';
    public final const E7 = 'e7';
    public final const E8 = 'e8';

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
            self::E5,
            self::E6,
            self::E7,
            self::E8,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You are not logged in', 'user-state-mutations'),
            self::E2 => $this->__('Please supply your username or email', 'user-state-mutations'),
            self::E3 => $this->__('Please supply your password', 'user-state-mutations'),
            self::E4 => $this->__('You are already logged in', 'user-state-mutations'),
            self::E5 => $this->__('There is no registered user with username \'%s\'', 'user-state-mutations'),
            self::E6 => $this->__('There is no registered user with email \'%s\'', 'user-state-mutations'),
            self::E7 => $this->__('The password is incorrect', 'user-state-mutations'),
            self::E8 => $this->__('[%1$s] %2$s', 'user-state-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
