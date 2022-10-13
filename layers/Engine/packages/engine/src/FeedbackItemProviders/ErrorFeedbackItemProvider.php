<?php

declare(strict_types=1);

namespace PoP\Engine\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class ErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E1A = 'e1a';
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
            self::E1,
            self::E1A,
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
            self::E1 => $this->__('Operation \'%s\' is not available', 'engine'),
            self::E1A => $this->__('The operation is not available', 'engine'),
            self::E2 => $this->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'engine'),
            self::E4 => $this->__('The value to which the directive is applied is not an array or object', 'engine'),
            self::E5 => $this->__('No composed directives were provided to \'%s\'', 'engine'),
            self::E6 => $this->__('There is no property \'%s\' in the application state', 'engine'),
            self::E7 => $this->__('%s', 'engine'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
