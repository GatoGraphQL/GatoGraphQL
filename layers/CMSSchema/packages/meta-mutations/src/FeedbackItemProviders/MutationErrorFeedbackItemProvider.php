<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';

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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('The term with ID \'%s\' already has meta entry for key \'%s\'', 'taxonomymeta-mutations'),
            self::E2 => $this->__('Meta key \'%s\' is not allowed', 'taxonomymeta-mutations'),
            self::E3 => $this->__('Meta keys \'%s\' are not allowed', 'taxonomymeta-mutations'),
            self::E4 => $this->__('The term with ID \'%s\' has no entry with meta key \'%s\'', 'taxonomymeta-mutations'),
            self::E5 => $this->__('The term with ID \'%s\' has no entry with meta key \'%s\' and value \'%s\'', 'taxonomymeta-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
