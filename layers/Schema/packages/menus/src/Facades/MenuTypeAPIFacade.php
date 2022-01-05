<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades;

use PoP\Engine\App;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuTypeAPIFacade
{
    public static function getInstance(): MenuTypeAPIInterface
    {
        /**
         * @var MenuTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(MenuTypeAPIInterface::class);
        return $service;
    }
}
