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
    public const E4 = 'e4';
    public const E5 = 'e5';
    public const E6 = 'e6';
    public const E7 = 'e7';
    public const E8 = 'e8';
    public const E9 = 'e9';
    public const E10 = 'e10';
    public const E11 = 'e11';
    public const E12 = 'e12';
    public const E13 = 'e13';

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
            self::E6 => $this->getTranslationAPI()->__('The oneof input object \'%s\' must receive exactly 1 input, but \'%s\' inputs were provided (\'%s\')', 'component-model'),
            self::E7 => $this->getTranslationAPI()->__('No input value was provided to the oneof input object \'%s\'', 'component-model'),
            self::E8 => $this->__('Argument \'%s\' does not expect an array, but array \'%s\' was provided', 'component-model'),
            self::E9 => $this->__('Argument \'%s\' expects an array, but value \'%s\' was provided', 'component-model'),
            self::E10 => $this->__('Argument \'%s\' cannot receive an array with `null` values', 'component-model'),
            self::E11 => $this->__('Argument \'%s\' cannot receive an array containing arrays as elements', 'component-model'),
            self::E12 => $this->__('Argument \'%s\' expects an array of arrays, but value \'%s\' was provided', 'component-model'),
            self::E13 => $this->__('Argument \'%s\' cannot receive an array of arrays with `null` values', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
