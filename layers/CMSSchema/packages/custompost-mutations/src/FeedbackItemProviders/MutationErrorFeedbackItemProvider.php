<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E5 = 'e5';
    public final const E6 = 'e6';
    public final const E7 = 'e7';
    public final const E8 = 'e8';
    public final const E9 = 'e9';
    public final const E10 = 'e10';
    public final const E11 = 'e11';
    public final const E12 = 'e12';
    public final const E13 = 'e13';
    public final const E14 = 'e14';
    public final const E15 = 'e15';
    public final const E16 = 'e16';
    public final const E17 = 'e17';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
            self::E3,
            self::E5,
            self::E6,
            self::E7,
            self::E8,
            self::E9,
            self::E10,
            self::E11,
            self::E12,
            self::E13,
            self::E14,
            self::E15,
            self::E16,
            self::E17,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to create or update custom posts', 'gatographql'),
            self::E2 => $this->__('Your user doesn\'t have permission for editing custom posts.', 'gatographql'),
            self::E3 => $this->__('Your user doesn\'t have permission for publishing custom posts.', 'gatographql'),
            self::E5 => $this->__('The custom post with ID \'%s\' is not of type \'%s\'.', 'gatographql'),
            self::E6 => $this->__('The custom post ID is missing', 'gatographql'),
            self::E7 => $this->__('There is no custom post with ID \'%s\'', 'gatographql'),
            self::E8 => $this->__('You don\'t have permission to edit custom post with ID \'%s\'', 'gatographql'),
            self::E9 => $this->__('You don\'t have permission to edit custom post type \'%s\'', 'gatographql'),
            self::E10 => $this->__('There is no custom post with slug path \'%s\' of type \'%s\'', 'gatographql'),
            self::E11 => $this->__('The custom post cannot have itself as parent', 'gatographql'),
            self::E12 => $this->__('The custom post with ID \'%s\' is an ancestor of the custom post with ID \'%s\'', 'gatographql'),
            self::E13 => $this->__('You must be logged in to delete custom posts', 'gatographql'),
            self::E14 => $this->__('You don\'t have permission to delete custom post with ID \'%s\'', 'gatographql'),
            self::E15 => $this->__('Your user doesn\'t have permission for deleting custom posts.', 'gatographql'),
            self::E16 => $this->__('The custom post with ID \'%s\' does not support being sent to the trash. Provide \'true\' in the \'force\' input to permanently delete it', 'gatographql'),
            self::E17 => $this->__('The custom post with ID \'%s\' has already been sent to the trash. Provide \'true\' in the \'force\' input to permanently delete it', 'gatographql'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
