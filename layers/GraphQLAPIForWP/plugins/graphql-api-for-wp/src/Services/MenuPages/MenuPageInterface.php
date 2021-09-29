<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Services\Menus\MenuInterface;

/**
 * Menu Page
 */
interface MenuPageInterface
{
    /**
     * Print the menu page HTML content
     */
    public function print(): void;
    public function getScreenID(): string;
    public function getMenu(): MenuInterface;
    public function setHookName(string $hookName): void;
    public function getHookName(): ?string;
    public function getMenuPageSlug(): string;
}
