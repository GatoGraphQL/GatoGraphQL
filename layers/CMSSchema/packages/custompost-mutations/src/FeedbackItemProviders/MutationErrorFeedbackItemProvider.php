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
            self::E6,
            self::E7,
            self::E8,
            self::E9,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to create or update custom posts', 'custompost-mutations'),
            self::E2 => $this->__('Your user doesn\'t have permission for editing custom posts.', 'custompost-mutations'),
            self::E3 => $this->__('Your user doesn\'t have permission for publishing custom posts.', 'custompost-mutations'),
            self::E6 => $this->__('The custom post ID is missing', 'custompost-mutations'),
            self::E7 => $this->__('There is no custom post with ID \'%s\'', 'custompost-mutations'),
            self::E8 => $this->__('You don\'t have permission to edit custom post with ID \'%s\'', 'custompost-mutations'),
            self::E9 => $this->__('You don\'t have permission to edit custom post type \'%s\'', 'custompost-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
