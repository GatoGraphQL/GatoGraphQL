<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class InputValueCoercionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E6 = 'e6';
    public final const E7 = 'e7';
    public final const E8 = 'e8';
    public final const E9 = 'e9';
    public final const E10 = 'e10';
    public final const E11 = 'e11';
    public final const E12 = 'e12';
    public final const E13 = 'e13';
    public final const E14 = 'e14';
    public final const E15 = 'e15';
    public final const E16 = 'e16';
    public final const E17 = 'e17';
    public final const E18 = 'e18';

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
            self::E15,
            self::E16,
            self::E17,
            self::E18,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('An object cannot be casted to type \'%s\'', 'component-model'),
            self::E2 => $this->__('The format for \'%s\' is not right for type \'%s\'', 'component-model'),
            self::E3 => $this->__('Type \'%s\' must be provided as a string', 'component-model'),
            self::E4 => $this->__('There is no input field \'%s\' in input object \'%s\''),
            self::E5 => $this->__('Mandatory input field \'%s\' in input object \'%s\' has not been provided'),
            self::E6 => $this->__('The oneof input object \'%s\' must receive exactly 1 input, but \'%s\' inputs were provided (\'%s\')', 'component-model'),
            self::E7 => $this->__('No input value was provided to the oneof input object \'%s\'', 'component-model'),
            self::E8 => $this->__('Argument \'%s\' does not expect an array, but array \'%s\' was provided', 'component-model'),
            self::E9 => $this->__('Argument \'%s\' expects an array, but value \'%s\' was provided', 'component-model'),
            self::E10 => $this->__('Argument \'%s\' cannot receive an array with `null` values', 'component-model'),
            self::E11 => $this->__('Argument \'%s\' cannot receive an array containing arrays as elements', 'component-model'),
            self::E12 => $this->__('Argument \'%s\' expects an array of arrays, but value \'%s\' was provided', 'component-model'),
            self::E13 => $this->__('Argument \'%s\' cannot receive an array of arrays with `null` values', 'component-model'),
            self::E14 => $this->__('Value \'%1$s\' for enum type \'%2$s\' is not valid (the only valid values are: \'%3$s\')', 'component-model'),
            self::E15 => $this->__('Input object of type \'%s\' cannot be casted from input value \'%s\'', 'component-model'),
            self::E16 => $this->__('Cannot cast value \'%s\' for type \'%s\'', 'component-model'),
            self::E17 => $this->__('Only strings or integers are allowed for type \'%s\'', 'component-model'),
            self::E18 => $this->__('Enum values can only be strings, value \'%s\' for type \'%s\' is not allowed', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
