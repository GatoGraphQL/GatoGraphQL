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
    public const E5 = 'e5';
    public const E6 = 'e6';
    public const E7 = 'e7';
    public const E8 = 'e8';

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
            self::E1 => $this->__('You must have capability \'%1$s\' to access directives in field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E2 => $this->__('You must have capability \'%1$s\' to access field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E3 => $this->__('You must have any capability from among \'%1$s\' to access directives in field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E4 => $this->__('You must have any capability from among \'%1$s\' to access field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E5 => $this->__('You must have role \'%1$s\' to access directives in field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E6 => $this->__('You must have role \'%1$s\' to access field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E7 => $this->__('You must have any role from among \'%1$s\' to access directives in field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            self::E8 => $this->__('You must have any role from among \'%1$s\' to access field(s) \'%2$s\' for type \'%3$s\'', 'user-roles-access-control'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1,
            self::E2,
            self::E3,
            self::E4,
            self::E5,
            self::E6,
            self::E7,
            self::E8
                => FeedbackCategories::ERROR,
            default
                => parent::getCategory($code),
        };
    }
}
