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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to upload files or edit media items', 'media-mutations'),
            self::E2 => $this->__('You don\'t have permission to upload files', 'media-mutations'),
            self::E4 => $this->__('You don\'t have permission to upload files for other users', 'media-mutations'),
            self::E5 => $this->__('There is no user with ID \'%s\'', 'media-mutations'),
            self::E6 => $this->__('There is no media item with ID \'%s\'', 'media-mutations'),
            self::E7 => $this->__('There is no media item with slug \'%s\'', 'media-mutations'),
            self::E8 => $this->__('You don\'t have permission to edit media item with id \'%s\'', 'media-mutations'),
            self::E9 => $this->__('You don\'t have permission to edit media items', 'polylang-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
