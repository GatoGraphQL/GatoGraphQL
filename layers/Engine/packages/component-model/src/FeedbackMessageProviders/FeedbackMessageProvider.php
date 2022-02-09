<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackMessageProviders;

use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Constants\Params;
use PoP\Root\FeedbackMessageProviders\AbstractFeedbackMessageProvider;
use PoP\Root\FeedbackMessage\FeedbackMessageCategories;

class FeedbackMessageProvider extends AbstractFeedbackMessageProvider
{
    public const W1 = 'w1';
    public const E1 = 'e1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::W1,
            self::E1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::W1 => $this->__('URL param \'' . Params::VERSION_CONSTRAINT_FOR_FIELDS . '\' expects the type and field name separated by \'' . Constants::TYPE_FIELD_SEPARATOR . '\' (eg: \'?' . Params::VERSION_CONSTRAINT_FOR_FIELDS . '[Post' . Constants::TYPE_FIELD_SEPARATOR . 'title]=^0.1\'), so the following value has been ignored: \'%s\'', 'component-model'),
            self::E1 => $this->__('Field \'%s\' is not a connection', 'pop-component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::W1 => FeedbackMessageCategories::WARNING,
            self::E1 => FeedbackMessageCategories::ERROR,
            default => parent::getCategory($code),
        };
    }
}
