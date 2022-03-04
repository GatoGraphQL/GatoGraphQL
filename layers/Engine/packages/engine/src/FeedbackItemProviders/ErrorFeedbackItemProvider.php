<?php

declare(strict_types=1);

namespace PoP\Engine\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class ErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E4 = 'e4';
    public const E5 = 'e5';
    public const E6 = 'e6';
    public const E7 = 'e7';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'engine'),
            self::E2 => $this->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'engine'),
            self::E3 => $this->__('The value for field \'%s\' (under property \'%s\') is not an array, so execution of this directive can\'t continue', 'engine'),
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
