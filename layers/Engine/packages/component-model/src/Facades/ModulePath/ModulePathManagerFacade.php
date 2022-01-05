<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModulePath;

use PoP\Root\App;
use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;

class ModulePathManagerFacade
{
    public static function getInstance(): ModulePathManagerInterface
    {
        /**
         * @var ModulePathManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ModulePathManagerInterface::class);
        return $service;
    }
}
