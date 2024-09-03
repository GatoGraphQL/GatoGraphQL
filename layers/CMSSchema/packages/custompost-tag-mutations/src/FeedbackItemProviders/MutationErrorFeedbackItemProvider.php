<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E4 = 'e4';
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
            self::E4,
            self::E6,
            self::E7,
            self::E8,
            self::E9,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to set tags on custom posts', 'custompost-tag-mutations'),
            self::E4 => $this->__('Tag taxonomy \'%s\' is not registered for custom post type \'%s\'', 'custompost-tag-mutations'),
            self::E6 => $this->__('There is no tag with ID \'%s\'', 'custompost-tag-mutations'),
            self::E7 => $this->__('On taxonomy \'%s\', there is no tag with ID \'%s\'', 'custompost-tag-mutations'),
            self::E8 => $this->__('There is no tag with slug \'%s\'', 'custompost-tag-mutations'),
            self::E9 => $this->__('On taxonomy \'%s\', there is no tag with slug \'%s\'', 'custompost-tag-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
