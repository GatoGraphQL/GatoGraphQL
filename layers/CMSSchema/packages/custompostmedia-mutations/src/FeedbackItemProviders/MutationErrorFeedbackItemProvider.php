<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('The media item is missing', 'custompostmedia-mutations'),
            self::E2 => $this->__('There is no media item with ID \'%s\'', 'custompostmedia-mutations'),
            self::E3 => $this->__('You must be logged in to set or remove the featured image on custom posts', 'custompost-mutations'),
            self::E4 => $this->__('Setting a featured image is not supported for custom post type \'%s\'', 'custompostmedia-mutations'),
            self::E5 => $this->__('There is no media item with slug \'%s\'', 'custompostmedia-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
