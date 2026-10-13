<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\Root\Feedback\FeedbackCategories;

class FieldResolutionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E2 = '2';
    public final const E3 = '3';
    public final const E4 = '4';
    public final const E5 = '5';
    public final const E6 = '6';
    public final const E7 = '7';
    public final const E8 = '8';
    public final const E9 = '9';
    public final const E10 = '10';
    public final const E11 = '11';
    public final const E12 = '12';
    public final const E13 = '13';
    public final const E14 = '14';
    public final const E15 = '15';
    public final const E16 = '16';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
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
            self::E15,
            self::E16,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E2 => $this->__('Field \'%s\' could not be processed due to the error(s) from its arguments', 'gatographql'),
            self::E3 => $this->__('Non-nullable field \'%s\' cannot return null', 'gatographql'),
            self::E4 => $this->__('Field \'%s\' must not return an array, but returned \'%s\'', 'gatographql'),
            self::E5 => $this->__('Field \'%s\' must return an array, but returned \'%s\'', 'gatographql'),
            self::E6 => $this->__('Field \'%s\' must not return an array with null items', 'gatographql'),
            self::E7 => $this->__('Array value in field \'%s\' must not contain arrays, but returned \'%s\'', 'gatographql'),
            self::E8 => $this->__('Field \'%s\' must return an array of arrays, but returned \'%s\'', 'gatographql'),
            self::E9 => $this->__('Field \'%s\' must not return an array of arrays with null items', 'gatographql'),
            self::E10 => $this->__('Directive \'%s\' set non-nullable field \'%s\' to null', 'gatographql'),
            self::E11 => $this->__('Directive \'%s\' added a null item to field \'%s\', whose items are non-nullable', 'gatographql'),
            self::E12 => $this->__('Directive \'%s\' added a null item to an inner list of field \'%s\', whose items are non-nullable', 'gatographql'),
            self::E13 => $this->__('Directive \'%s\' (or a directive nested within it) set non-nullable field \'%s\' to null', 'gatographql'),
            self::E14 => $this->__('Directive \'%s\' (or a directive nested within it) added a null item to field \'%s\', whose items are non-nullable', 'gatographql'),
            self::E15 => $this->__('Directive \'%s\' (or a directive nested within it) added a null item to an inner list of field \'%s\', whose items are non-nullable', 'gatographql'),
            self::E16 => $this->__('Value \'%s\' of field \'%s\' cannot be coerced to type \'%s\'', 'gatographql'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
