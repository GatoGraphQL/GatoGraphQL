<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E6 = 'e6';
    public final const E7 = 'e7';

    /**
     * @return string[]
     */
    public function getCodes(): array
    {
        return [
            self::E1,
            self::E2,
            self::E6,
            self::E7,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to create or update taxonomies', 'taxonomy-mutations'),
            self::E2 => $this->__('Your user doesn\'t have permission for editing taxonomies.', 'taxonomy-mutations'),
            self::E6 => $this->__('The taxonomy ID is missing', 'taxonomy-mutations'),
            self::E7 => $this->__('There is no taxonomy with ID \'%s\'', 'taxonomy-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
