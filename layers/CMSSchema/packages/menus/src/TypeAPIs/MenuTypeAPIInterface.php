<?php

declare(strict_types=1);

namespace PoPCMSSchema\Menus\TypeAPIs;

use PoPCMSSchema\Menus\ObjectModels\MenuItem;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MenuTypeAPIInterface
{
    public function getMenu(string | int $menuID): ?object;
    /**
     * @return MenuItem[]
     */
    public function getMenuItems(string | int | object $menuObjectOrID): array;
    public function getMenuID(object $menu): string | int;
    public function getMenuIDFromMenuName(string $menuName): string | int | null;
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string|int|object>
     */
    public function getMenus(array $query, array $options = []): array;
    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     */
    public function getMenuCount(array $query, array $options = []): int;
}
