<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\FeedbackItemProviders;

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
    public final const E9 = 'e9';

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
            self::E9,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You are not logged in', 'user-state-mutations'),
            self::E2 => $this->__('The comment author\'s name is missing', 'media-mutations'),
            self::E3 => $this->__('The comment author\'s email is missing', 'media-mutations'),
            self::E4 => $this->__('The custom post ID is missing', 'media-mutations'),
            self::E5 => $this->__('The comment is empty', 'media-mutations'),
            self::E6 => $this->__('There is no (parent) comment with ID \'%s\'', 'media-mutations'),
            self::E7 => $this->__('There is no custom post with ID \'%s\'', 'media-mutations'),
            self::E8 => $this->__('Comments are not supported for custom post type \'%s\'', 'media-mutations'),
            self::E9 => $this->__('Comments are not open for custom post with ID \'%s\'', 'media-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
