<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Services\Menus\MenuInterface;
use PoP\Root\Services\ServiceInterface;

/**
 * Menu Page
 */
interface MenuPageInterface extends ServiceInterface
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
    public function getMenuPageTitle(): string;
}
