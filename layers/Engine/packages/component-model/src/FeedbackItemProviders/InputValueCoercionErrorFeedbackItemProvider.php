<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class InputValueCoercionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
            self::E3,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('An object cannot be casted to type \'%s\'', 'component-model'),
            self::E2 => $this->__('The format for \'%s\' is not right for type \'%s\'', 'component-model'),
            self::E3 => $this->__('Type \'%s\' must be provided as a string', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
