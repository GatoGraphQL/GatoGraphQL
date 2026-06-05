<?php

declare(strict_types=1);

namespace PoPSchema\DirectiveCommons\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E6 = 'e6';
    public final const E7 = 'e7';
    public final const W1 = 'w1';
    public final const W2 = 'w2';

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
            self::W1,
            self::W2,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not of a supported type', 'gatographql'),
            self::E2 => $this->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not a string', 'gatographql'),
            self::E3 => $this->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not a bool', 'gatographql'),
            self::E4 => $this->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not an integer', 'gatographql'),
            self::E5 => $this->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not a float', 'gatographql'),
            self::E6 => $this->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not a JSON object', 'gatographql'),
            self::E7 => $this->__('Directive \'%s\' from field \'%s\' cannot be applied on object with ID \'%s\' because it is not an array', 'gatographql'),
            self::W1 => $this->__('Dynamic variable with name \'%s\' had already been set, had its value overridden', 'gatographql'),
            self::W2 => $this->__('Dynamic variable with name \'%s\' had already been set for object with ID \'%s\', had its value overridden', 'gatographql'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1,
            self::E2,
            self::E3,
            self::E4,
            self::E5,
            self::E6,
            self::E7
                => FeedbackCategories::ERROR,
            self::W1,
            self::W2
                => FeedbackCategories::WARNING,
            default
                => parent::getCategory($code),
        };
    }
}
