<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E4 = 'e4';
    public const E5 = 'e5';

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
            self::E1 => $this->__('You must be logged in to create or update custom posts', 'custompost-mutations'),
            self::E2 => $this->__('Your user doesn\'t have permission for editing custom posts.', 'custompost-mutations'),
            self::E3 => $this->__('Your user doesn\'t have permission for publishing custom posts.', 'custompost-mutations'),
            self::E4 => $this->__('Either the title, or the content, must be provided', 'custompost-mutations'),
            self::E5 => $this->__('Status \'%s\' is not supported', 'custompost-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
