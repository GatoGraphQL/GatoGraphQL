<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades;

use PoP\Root\App;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuTypeAPIFacade
{
    public static function getInstance(): MenuTypeAPIInterface
    {
        /**
         * @var MenuTypeAPIInterface
         */
        $service = App::getContainer()->get(MenuTypeAPIInterface::class);
        return $service;
    }
}
