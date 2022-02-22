<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Constants\Params;
use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const W1 = 'w1';
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

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::W1,
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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::W1 => $this->__('URL param \'' . Params::VERSION_CONSTRAINT_FOR_FIELDS . '\' expects the type and field name separated by \'' . Constants::TYPE_FIELD_SEPARATOR . '\' (eg: \'?' . Params::VERSION_CONSTRAINT_FOR_FIELDS . '[Post' . Constants::TYPE_FIELD_SEPARATOR . 'title]=^0.1\'), so the following value has been ignored: \'%s\'', 'component-model'),
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
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::W1
                => FeedbackCategories::WARNING,
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
            self::E12
                => FeedbackCategories::ERROR,
            default
                => parent::getCategory($code),
        };
    }
}
