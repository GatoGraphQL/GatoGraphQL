<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModulePath;

use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ModulePathHelpersFacade
{
    public static function getInstance(): ModulePathHelpersInterface
    {
        /**
         * @var ModulePathHelpersInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(ModulePathHelpersInterface::class);
        return $service;
    }
}
