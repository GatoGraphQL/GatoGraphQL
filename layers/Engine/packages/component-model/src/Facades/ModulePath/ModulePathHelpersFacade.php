<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModulePath;

use PoP\Root\App;
use PoP\ComponentModel\ModulePath\ModulePathHelpersInterface;

class ModulePathHelpersFacade
{
    public static function getInstance(): ModulePathHelpersInterface
    {
        /**
         * @var ModulePathHelpersInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ModulePathHelpersInterface::class);
        return $service;
    }
}
