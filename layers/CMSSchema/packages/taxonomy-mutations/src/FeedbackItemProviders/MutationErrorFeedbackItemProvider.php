<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E3 = 'e3';
    public final const E4 = 'e4';
    public final const E5 = 'e5';
    public final const E6 = 'e6';
    public final const E7 = 'e7';
    public final const E8 = 'e8';
    public final const E9 = 'e9';
    public final const E10 = 'e10';
    public final const E11 = 'e11';
    public final const E12 = 'e12';

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
            self::E8,
            self::E9,
            self::E10,
            self::E11,
            self::E12,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to mutate taxonomy terms', 'taxonomy-mutations'),
            self::E2 => $this->__('Your user doesn\'t have permission for editing taxonomy \'%s\'.', 'taxonomy-mutations'),
            self::E3 => $this->__('Your user doesn\'t have permission for deleting taxonomy term with ID \'%s\'.', 'taxonomy-mutations'),
            self::E4 => $this->__('The taxonomy ID is missing', 'taxonomy-mutations'),
            self::E5 => $this->__('There is no taxonomy with name \'%s\'', 'taxonomy-mutations'),
            self::E6 => $this->__('There is no term with ID \'%s\'', 'taxonomy-mutations'),
            self::E7 => $this->__('On taxonomy \'%s\', there is no term with ID \'%s\'', 'taxonomy-mutations'),
            self::E8 => $this->__('There is no term with slug \'%s\'', 'taxonomy-mutations'),
            self::E9 => $this->__('On taxonomy \'%s\', there is no term with slug \'%s\'', 'taxonomy-mutations'),
            self::E10 => $this->__('Your user doesn\'t have permission to assign terms to taxonomy \'%s\'.', 'taxonomy-mutations'),
            self::E11 => $this->__('There is no custom post type registered for ID \'%s\'.', 'taxonomy-mutations'),
            self::E12 => $this->__('Taxonomy \'%s\' (for terms with IDs \'%s\') is not valid for custom post type \'%s\'', 'taxonomy-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
