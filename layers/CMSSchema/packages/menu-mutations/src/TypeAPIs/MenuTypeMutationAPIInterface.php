<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\TypeAPIs;

use PoPCMSSchema\MenuMutations\Exception\MenuCRUDMutationException;

interface MenuTypeMutationAPIInterface
{
    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    public function createMenu(
        array $menuData,
    ): string|int;
    /**
     * @throws MenuCRUDMutationException In case of error
     * @param array<string,mixed> $menuData
     */
    public function updateMenu(
        string|int $menuID,
        array $menuData,
    ): void;
    public function canUserEditMenus(
        string|int $userID,
    ): bool;
    public function canUserEditMenu(
        string|int $userID,
        string|int $menuID,
    ): bool;
}
