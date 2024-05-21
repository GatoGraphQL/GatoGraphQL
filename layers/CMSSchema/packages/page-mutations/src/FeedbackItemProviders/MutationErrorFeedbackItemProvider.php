<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E2 = 'e2';
    public final const E3 = 'e3';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E2,
            self::E3,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E2 => $this->__('Your user doesn\'t have permission for editing pages.', 'page-mutations'),
            self::E3 => $this->__('Your user doesn\'t have permission for publishing pages.', 'page-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
