<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E7 = 'e7';
    public final const E8 = 'e8';
    public final const E9 = 'e9';
    public final const E10 = 'e10';
    public final const E11 = 'e11';
    public final const E12 = 'e12';
    public final const E13 = 'e13';
    public final const E14 = 'e14';
    public final const E15 = 'e15';

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
            self::E7,
            self::E8,
            self::E9,
            self::E10,
            self::E11,
            self::E12,
            self::E13,
            self::E14,
            self::E15,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('There is no user with ID \'%s\'', 'gatographql'),
            self::E2 => $this->__('There is no user with username \'%s\'', 'gatographql'),
            self::E3 => $this->__('There is no user with email \'%s\'', 'gatographql'),
            self::E4 => $this->__('The logged-in user doesn\'t have the ability to edit user with ID \'%s\'', 'gatographql'),
            self::E5 => $this->__('You must be logged in to perform this action', 'gatographql'),
            self::E7 => $this->__('You don\'t have permission to create users', 'gatographql'),
            self::E8 => $this->__('You don\'t have permission to delete user with ID \'%s\'', 'gatographql'),
            self::E9 => $this->__('You don\'t have permission to edit user roles', 'gatographql'),
            self::E10 => $this->__('The username \'%s\' already exists', 'gatographql'),
            self::E11 => $this->__('The email \'%s\' already belongs to another user', 'gatographql'),
            self::E12 => $this->__('The email \'%s\' is not valid', 'gatographql'),
            self::E13 => $this->__('The user role \'%s\' does not exist', 'gatographql'),
            self::E14 => $this->__('There is no user with ID \'%s\' to reassign the content to', 'gatographql'),
            self::E15 => $this->__('You cannot delete your own user account', 'gatographql'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
