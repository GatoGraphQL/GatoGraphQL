<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E4 = 'e4';

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
            self::E1 => $this->__('You must have capability \'%1$s\' to access directives in field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E2 => $this->__('You must have capability \'%1$s\' to access field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E3 => $this->__('You must have any capability from among \'%1$s\' to access directives in field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E4 => $this->__('You must have any capability from among \'%1$s\' to access field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
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
