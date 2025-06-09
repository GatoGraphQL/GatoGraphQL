<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class InputValueCoercionErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
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
            self::E1 => $this->__('The format for type \'%s\' is not correct: it must be satisfied via regex /(\+{1}[0-9]{1,3}[0-9]{8,9})/', 'extended-schema-commons'),
            self::E2 => $this->__('The format for type \'%s\' is not correct: it must be satisfied via regex /^{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}}?$/', 'extended-schema-commons'),
            self::E3 => $this->__('Type \'%s\' must receive arrays as values (even for single-item values, eg: `{ some_key: [ "some value" ] }`), but received: `%s`', 'extended-schema-commons'),
            self::E4 => $this->__('The format for type \'%s\' is not correct: it must be satisfied via regex /^[a-zA-Z_][a-zA-Z0-9_]*$/', 'extended-schema-commons'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
