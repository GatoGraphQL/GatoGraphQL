<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E6 = 'e6';
    public final const E7 = 'e7';
    public final const E8 = 'e8';
    public final const E9 = 'e9';
    public final const E10 = 'e10';
    public final const E11 = 'e11';
    public final const E12 = 'e12';
    public final const E13 = 'e13';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
            self::E4,
            self::E5,
            self::E6,
            self::E7,
            self::E8,
            self::E9,
            self::E10,
            self::E11,
            self::E12,
            self::E13,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to upload files or edit media items', 'gatographql'),
            self::E2 => $this->__('You don\'t have permission to upload files', 'gatographql'),
            self::E4 => $this->__('You don\'t have permission to upload files for other users', 'gatographql'),
            self::E5 => $this->__('There is no user with ID \'%s\'', 'gatographql'),
            self::E6 => $this->__('There is no media item with ID \'%s\'', 'gatographql'),
            self::E7 => $this->__('There is no media item with slug \'%s\'', 'gatographql'),
            self::E8 => $this->__('You don\'t have permission to edit media item with id \'%s\'', 'gatographql'),
            self::E9 => $this->__('You don\'t have permission to edit media items', 'gatographql'),
            self::E10 => $this->__('You must be logged in to delete media items', 'gatographql'),
            self::E11 => $this->__('You don\'t have permission to delete media item with ID \'%s\'', 'gatographql'),
            self::E12 => $this->__('The media item with ID \'%s\' does not support being sent to the trash. Provide \'true\' in the \'force\' input to permanently delete it', 'gatographql'),
            self::E13 => $this->__('The media item with ID \'%s\' has already been sent to the trash. Provide \'true\' in the \'force\' input to permanently delete it', 'gatographql'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
