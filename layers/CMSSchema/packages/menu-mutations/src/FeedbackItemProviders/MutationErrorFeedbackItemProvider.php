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
        ];
    }

    public function getMessagePlaceholder(string $code): string
    {
        return match ($code) {
            self::E1 => $this->__('You must be logged in to create menus or edit menus', 'menu-mutations'),
            self::E2 => $this->__('You don\'t have permission to create menus', 'menu-mutations'),
            self::E6 => $this->__('There is no menu with ID \'%s\'', 'menu-mutations'),
            self::E7 => $this->__('There is no menu with slug \'%s\'', 'menu-mutations'),
            self::E8 => $this->__('You don\'t have permission to edit menu with id \'%s\'', 'menu-mutations'),
            self::E9 => $this->__('You don\'t have permission to edit menus', 'menu-mutations'),
            default => parent::getMessagePlaceholder($code),
        };
    }

    public function getCategory(string $code): string
    {
        return FeedbackCategories::ERROR;
    }
}
