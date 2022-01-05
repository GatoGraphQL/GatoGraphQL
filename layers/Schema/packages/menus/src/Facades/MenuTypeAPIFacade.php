<?php

declare(strict_types=1);

namespace PoPSchema\Menus\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Menus\TypeAPIs\MenuTypeAPIInterface;

class MenuTypeAPIFacade
{
    public static function getInstance(): MenuTypeAPIInterface
    {
        /**
         * @var MenuTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(MenuTypeAPIInterface::class);
        return $service;
    }
}
