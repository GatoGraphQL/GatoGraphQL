<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Menus\Menu;

trait GraphQLAPIMenuPageTrait
{
    public function getMenuName(): string
    {
        return Menu::getName();
    }
}
