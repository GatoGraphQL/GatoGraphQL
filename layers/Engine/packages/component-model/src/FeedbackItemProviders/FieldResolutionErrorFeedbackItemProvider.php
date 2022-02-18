<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class FieldResolutionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = '1';
    public const E2 = '2';
    public const E3 = '3';
    public const E4 = '4';
    public const E5 = '5';
    public const E6 = '6';
    public const E7 = '7';
    public const E8 = '8';
    public const E9 = '9';
    public const E10 = '10';
    public const E11 = '11';
    public const E12 = '12';
    public const E13 = '13';
    public const E14 = '14';

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
            self::E8,
            self::E9,
            self::E10,
            self::E11,
            self::E12,
            self::E13,
            self::E14,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('There is no field \'%s\' on type \'%s\' and ID \'%s\'', 'component-model'),
            self::E2 => null,
            self::E3 => null,
            self::E4 => null,
            self::E5 => null,
            self::E6 => null,
            self::E7 => null,
            self::E8 => null,
            self::E9 => null,
            self::E10 => null,
            self::E11 => null,
            self::E12 => null,
            self::E13 => null,
            self::E14 => null,
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
