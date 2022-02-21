<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class InputValueCoercionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';
    public const E3 = 'e3';
    public const E4 = 'e4';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('Type \'%s\' must be provided with format \'%s\'', 'component-model'),
            self::E2 => $this->__('Value \'%1$s\' for type \'%2$s\' is not valid (the only valid values are: \'%3$s\')', 'component-model'),
            self::E3 => $this->__('The format for type \'%s\' is not right: it must be satisfied via regex /(\+{1}[0-9]{1,3}[0-9]{8,9})/', 'component-model'),
            self::E4 => $this->__('The format for type \'%s\' is not right: it must be satisfied via regex /^{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}}?$/', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
