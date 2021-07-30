<?php

declare(strict_types=1);

namespace PoPSchema\Menus\TypeAPIs;

use PoPSchema\Menus\ObjectModels\MenuItem;

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
     * @param array<string, mixed> $options
     * @return array<string|int|object>
     */
    public function getMenus(array $options = []): array;
}
