<?php

declare(strict_types=1);

namespace PoP\Engine\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E4 = 'e4';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'examples-for-pop'),
            self::E2 => $this->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'examples-for-pop'),
            self::E3 => $this->__('The value for field \'%s\' (under property \'%s\') is not an array, so execution of this directive can\'t continue', 'examples-for-pop'),
            self::E4 => $this->__('The value for field \'%s\' is not an array, so execution of this directive can\'t continue', 'examples-for-pop'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1,
            self::E2,
            self::E3,
            self::E4
                => FeedbackCategories::ERROR,
            default
                => parent::getCategory($code),
        };
    }
}
