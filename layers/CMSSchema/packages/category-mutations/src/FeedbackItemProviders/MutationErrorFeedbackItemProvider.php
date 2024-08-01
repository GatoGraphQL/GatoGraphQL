<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E5 = 'e5';
    public final const E7 = 'e7';
    public final const E8 = 'e8';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E5,
            self::E7,
            self::E8,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to mutate category terms', 'category-mutations'),
            self::E5 => $this->__('There is no category taxonomy with name \'%s\'', 'category-mutations'),
            self::E7 => $this->__('There is no category term with ID \'%s\'', 'category-mutations'),
            self::E8 => $this->__('There is no category term with slug \'%s\'', 'category-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
