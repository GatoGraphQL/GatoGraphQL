<?php

declare(strict_types=1);

namespace PoP\Hooks\Facades;

use PoP\Root\App;
use PoP\Hooks\HooksAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class HooksAPIFacade
{
    public static function getInstance(): HooksAPIInterface
    {
        /**
         * @var HooksAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(HooksAPIInterface::class);
        return $service;
    }
}
