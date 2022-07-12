<?php

declare(strict_types=1);

namespace PoP\Engine\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class ErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E2 = 'e2';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E6 = 'e6';
    public final const E7 = 'e7';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E2,
            self::E4,
            self::E5,
            self::E6,
            self::E7,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E2 => $this->__('Field(s) \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'engine'),
            self::E4 => $this->__('The value for field \'%s\' is not an array, so execution of this directive can\'t continue', 'engine'),
            self::E5 => $this->__('No composed directives were provided to \'%s\'', 'engine'),
            self::E6 => $this->__('There is no property \'%s\' in the application state', 'engine'),
            self::E7 => $this->__('Traversing the array produced error: %s', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
