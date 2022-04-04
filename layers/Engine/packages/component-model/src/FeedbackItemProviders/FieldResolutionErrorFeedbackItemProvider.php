<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class FieldResolutionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = '1';
    public final const E2 = '2';
    public final const E3 = '3';
    public final const E4 = '4';
    public final const E5 = '5';
    public final const E6 = '6';
    public final const E7 = '7';
    public final const E8 = '8';
    public final const E9 = '9';
    public final const E10 = '10';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('There is no field \'%s\' on type \'%s\' and ID \'%s\'', 'component-model'),
            self::E2 => $this->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'component-model'),
            self::E3 => $this->__('Non-nullable field \'%s\' cannot return null', 'component-model'),
            self::E4 => $this->__('Field \'%s\' must not return an array, but returned \'%s\'', 'component-model'),
            self::E5 => $this->__('Field \'%s\' must return an array, but returned \'%s\'', 'component-model'),
            self::E6 => $this->__('Field \'%s\' must not return an array with null items', 'component-model'),
            self::E7 => $this->__('Array value in field \'%s\' must not contain arrays, but returned \'%s\'', 'component-model'),
            self::E8 => $this->__('Field \'%s\' must return an array of arrays, but returned \'%s\'', 'component-model'),
            self::E9 => $this->__('Field \'%s\' must not return an array of arrays with null items', 'component-model'),
            self::E10 => $this->__('No FieldResolver for object type \'%s\' processes field \'%s\' for object with ID \'%s\'', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
