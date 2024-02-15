<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class InputValueCoercionGraphQLSpecErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E_5_6_1_1 = '5.6.1[1]';
    public final const E_5_6_1_2 = '5.6.1[2]';
    public final const E_5_6_1_3 = '5.6.1[3]';
    public final const E_5_6_1_4 = '5.6.1[4]';
    public final const E_5_6_1_6 = '5.6.1[6]';
    public final const E_5_6_1_7 = '5.6.1[7]';
    public final const E_5_6_1_8 = '5.6.1[8]';
    public final const E_5_6_1_9 = '5.6.1[9]';
    public final const E_5_6_1_10 = '5.6.1[10]';
    public final const E_5_6_1_11 = '5.6.1[11]';
    public final const E_5_6_1_12 = '5.6.1[12]';
    public final const E_5_6_1_13 = '5.6.1[13]';
    public final const E_5_6_1_14 = '5.6.1[14]';
    public final const E_5_6_1_15 = '5.6.1[15]';
    public final const E_5_6_1_16 = '5.6.1[16]';
    public final const E_5_6_1_17 = '5.6.1[17]';
    public final const E_5_6_1_18 = '5.6.1[18]';
    public final const E_5_6_1_19 = '5.6.1[19]';
    public final const E_5_6_1_20 = '5.6.1[20]';

    protected function getNamespace(): string
    {
        return 'gql';
    }

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E_5_6_1_1,
            self::E_5_6_1_2,
            self::E_5_6_1_3,
            self::E_5_6_1_4,
            self::E_5_6_1_6,
            self::E_5_6_1_7,
            self::E_5_6_1_8,
            self::E_5_6_1_9,
            self::E_5_6_1_10,
            self::E_5_6_1_11,
            self::E_5_6_1_12,
            self::E_5_6_1_13,
            self::E_5_6_1_14,
            self::E_5_6_1_15,
            self::E_5_6_1_16,
            self::E_5_6_1_17,
            self::E_5_6_1_18,
            self::E_5_6_1_19,
            self::E_5_6_1_20,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E_5_6_1_1 => $this->__('An object cannot be cast to type \'%s\'', 'component-model'),
            self::E_5_6_1_2 => $this->__('The format for \'%s\' is not correct for type \'%s\'', 'component-model'),
            self::E_5_6_1_3 => $this->__('Type \'%s\' must be provided as a string', 'component-model'),
            self::E_5_6_1_4 => $this->__('Argument \'%s\' of type \'%s\' cannot be `null`', 'component-model'),
            self::E_5_6_1_6 => $this->__('The oneof input object \'%s\' must receive exactly 1 input, but %s inputs were provided (\'%s\')', 'component-model'),
            self::E_5_6_1_7 => $this->__('No input value was provided to the oneof input object \'%s\'', 'component-model'),
            self::E_5_6_1_8 => $this->__('Argument \'%s\' does not expect an array, but array \'%s\' was provided', 'component-model'),
            self::E_5_6_1_9 => $this->__('Argument \'%s\' expects an array, but value \'%s\' was provided', 'component-model'),
            self::E_5_6_1_10 => $this->__('Argument \'%s\' cannot receive an array with `null` values', 'component-model'),
            self::E_5_6_1_11 => $this->__('Argument \'%s\' cannot receive an array containing arrays as elements', 'component-model'),
            self::E_5_6_1_12 => $this->__('Argument \'%s\' expects an array of arrays, but value \'%s\' was provided', 'component-model'),
            self::E_5_6_1_13 => $this->__('Argument \'%s\' cannot receive an array of arrays with `null` values', 'component-model'),
            self::E_5_6_1_14 => $this->__('Value \'%1$s\' for enum type \'%2$s\' is not valid (the only valid values are: \'%3$s\')', 'component-model'),
            self::E_5_6_1_15 => $this->__('Input object of type \'%s\' cannot be cast from input value \'%s\'', 'component-model'),
            self::E_5_6_1_16 => $this->__('Cannot cast value \'%s\' for type \'%s\'', 'component-model'),
            self::E_5_6_1_17 => $this->__('Only strings or integers are allowed for type \'%s\'', 'component-model'),
            self::E_5_6_1_18 => $this->__('Enum values can only be strings, value \'%s\' for type \'%s\' is not allowed', 'component-model'),
            self::E_5_6_1_19 => $this->__('Property \'%s\' in oneof input object \'%s\' cannot receive `null`', 'component-model'),
            self::E_5_6_1_20 => $this->__('Argument \'%s\' of type \'%s\' cannot be an object', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }

    public function getSpecifiedByURL(string $code): ?string
    {
        return 'https://spec.graphql.org/draft/#sec-Values-of-Correct-Type';
    }
}
