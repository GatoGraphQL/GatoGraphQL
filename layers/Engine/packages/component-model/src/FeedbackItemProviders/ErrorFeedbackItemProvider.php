<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

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
    public const E8 = 'e8';
    public const E9 = 'e9';
    public const E10 = 'e10';
    public const E11 = 'e11';
    public const E12 = 'e12';
    public const E13 = 'e13';
    public const E14 = 'e14';
    public const E15 = 'e15';
    public const E16 = 'e16';
    public const E17 = 'e17';
    public const E18 = 'e18';
    public const E19 = 'e19';
    public const E20 = 'e20';
    public const E21 = 'e21';
    public const E22 = 'e22';
    public const E23 = 'e23';
    public const E24 = 'e24';
    public const E25 = 'e25';
    public const E26 = 'e26';
    public const E27 = 'e27';

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
            self::E19,
            self::E20,
            self::E21,
            self::E22,
            self::E23,
            self::E24,
            self::E25,
            self::E26,
            self::E27,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('Field \'%s\' is not a connection', 'component-model'),
            self::E2 => $this->__('Field \'%s\' could not be resolved due to its nested error(s)', 'component-model'),
            self::E3 => $this->__('Resolving field \'%s\' triggered exception: \'%s\'', 'component-model'),
            self::E4 => $this->__('Resolving field \'%s\' triggered an exception, please contact the admin', 'component-model'),
            self::E5 => $this->__('Directive \'%s\' could not be resolved due to its nested error(s)', 'component-model'),
            self::E6 => $this->__('Resolving mutation \'%s\' triggered exception: \'%s\'', 'component-model'),
            self::E7 => $this->__('Resolving mutation \'%s\' triggered an exception, please contact the admin', 'component-model'),
            self::E8 => $this->__('No TypeResolver resolves object \'%s\'', 'component-model'),
            self::E9 => $this->__('The DataLoader can\'t load data for object of type \'%s\' with ID \'%s\'', 'component-model'),
            self::E10 => $this->__('Either the DataLoader can\'t load data, or no TypeResolver resolves, object with ID \'%s\'', 'component-model'),
            self::E11 => $this->__('Resolving directive \'%s\' triggered exception: \'%s\'', 'component-model'),
            self::E12 => $this->__('Resolving directive \'%s\' triggered an exception, please contact the admin', 'component-model'),
            self::E13 => $this->__('Corrupted data: Object with ID \'%s\' doesn\'t exist', 'component-model'),
            self::E14 => $this->__('Expression \'%s\' is undefined', 'component-model'),
            self::E15 => $this->__('For directive \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'component-model'),
            self::E16 => $this->__('There is no field \'%s\' on type \'%s\'', 'component-model'),
            self::E17 => $this->__('For field \'%s\', casting value \'%s\' for argument \'%s\' to type \'%s\' failed', 'component-model'),
            self::E18 => $this->__('Validation failed for directives in fields \'%s\'', 'component-model') ,
            self::E19 => $this->__('Validation failed for fields \'%s\'', 'component-model'),
            self::E20 => $this->__('There is no directive with name \'%s\'', 'component-model'),
            self::E21 => $this->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field(s) \'%s\'', 'component-model'),
            self::E22 => $this->__('No DirectiveResolver processes directive with name \'%s\' and arguments \'%s\' in field \'%s\'', 'component-model'),
            self::E23 => $this->__('Directive \'%s\' can be executed only once for field(s) \'%s\'', 'component-model'),
            self::E24 => $this->__('Argument \'%1$s\' in %2$s \'%3$s\' cannot be empty', 'component-model'),
            self::E25 => $this->__('Arguments \'%1$s\' in %2$s \'%3$s\' cannot be empty', 'component-model'),
            self::E26 => $this->__('There is no field \'%s\' on type \'%s\' satisfying version constraint \'%s\'', 'component-model'),
            self::E27 => $this->__('There is no field \'%s\' on type \'%s\'', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
