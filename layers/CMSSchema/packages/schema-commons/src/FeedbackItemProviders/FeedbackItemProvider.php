<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class FeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public const E1 = 'e1';
    public const E2 = 'e2';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('The value for input field \'%s\' in input object \'%s\' cannot be below \'%s\'', 'schema-commons'),
            self::E2 => $this->__('The value for input field \'%s\' in input object \'%s\' cannot be above \'%s\', but \'%s\' was provided', 'schema-commons'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return match ($code) {
            self::E1,
            self::E2
                => FeedbackCategories::ERROR,
            default
                => parent::getCategory($code),
        };
    }
}
