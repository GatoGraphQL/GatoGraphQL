<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

/**
 * Menu Page
 */
interface MenuPageInterface
{
    /**
     * Print the menu page HTML content
     *
     * @return void
     */
    public function print(): void;
    public function getScreenID(): string;
}
