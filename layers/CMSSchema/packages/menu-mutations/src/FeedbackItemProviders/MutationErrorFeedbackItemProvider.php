<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\FeedbackItemProviders;

use PoP\Root\FeedbackItemProviders\AbstractFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;

class MutationErrorFeedbackItemProvider extends AbstractFeedbackItemProvider
{
    public final const E1 = 'e1';
    public final const E2 = 'e2';
    public final const E6 = 'e6';
    public final const E7 = 'e7';
    public final const E8 = 'e8';
    public final const E9 = 'e9';
    public final const E10 = 'e10';
    public final const E11 = 'e11';

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
            self::E8,
            self::E9,
            self::E10,
            self::E11,
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to create menus or edit menus', 'gatographql'),
            self::E2 => $this->__('You don\'t have permission to create menus', 'gatographql'),
            self::E6 => $this->__('There is no menu with ID \'%s\'', 'gatographql'),
            self::E7 => $this->__('There is no menu with slug \'%s\'', 'gatographql'),
            self::E8 => $this->__('You don\'t have permission to edit menu with id \'%s\'', 'gatographql'),
            self::E9 => $this->__('You don\'t have permission to edit menus', 'gatographql'),
            self::E10 => $this->__('You must be logged in to delete menus', 'gatographql'),
            self::E11 => $this->__('You don\'t have permission to delete menu with ID \'%s\'', 'gatographql'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
