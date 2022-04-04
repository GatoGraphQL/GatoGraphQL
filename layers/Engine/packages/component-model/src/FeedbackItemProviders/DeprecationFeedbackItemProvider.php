<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class DeprecationFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const D1 = 'd1';
    public final const D2 = 'd2';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::D1,
            self::D2,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::D1 => $this->__('Field \'%s\' is deprecated: %s', 'component-model'),
            self::D1 => $this->__('Directive \'%s\' is deprecated: %s', 'component-model'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::DEPRECATION;
    }
}
