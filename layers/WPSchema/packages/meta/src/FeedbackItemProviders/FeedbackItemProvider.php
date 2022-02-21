<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('There is no meta with key \'%s\'', 'meta'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1 => FeedbackCategories::ERROR,
            default => parent::getCategory($code),
        };
    }
}
