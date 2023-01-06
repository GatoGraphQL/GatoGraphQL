<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Constants\Params;
use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class WarningFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const W1 = 'w1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::W1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::W1 => sprintf(
                $this->__('URL param \'%s\' expects the type and field name separated by \'%s\' (eg: \'%s\'), so the following value has been ignored: ', 'component-model'),
                Params::VERSION_CONSTRAINT_FOR_FIELDS,
                Constants::TYPE_FIELD_SEPARATOR,
                '?' . Params::VERSION_CONSTRAINT_FOR_FIELDS . '[Post' . Constants::TYPE_FIELD_SEPARATOR . 'title]=^0.1'
            ) . '\'%s\'',
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::WARNING;
    }
}
