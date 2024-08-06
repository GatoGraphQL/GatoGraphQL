<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
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
            self::E1 => $this->__('You must be logged in to mutate tag terms', 'tag-mutations'),
            self::E5 => $this->__('There is no tag taxonomy with name \'%s\'', 'tag-mutations'),
            self::E6 => $this->__('There is no tag term with ID \'%s\'', 'tag-mutations'),
            self::E7 => $this->__('On tag \'%s\', there is no term with ID \'%s\'', 'tag-mutations'),
            self::E8 => $this->__('There is no tag term with slug \'%s\'', 'tag-mutations'),
            self::E9 => $this->__('On tag \'%s\', there is no term with slug \'%s\'', 'tag-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getTag(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
