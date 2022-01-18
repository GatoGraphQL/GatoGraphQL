<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Modules;

use PoP\Root\App;
use PoP\ComponentModel\Modules\ModuleHelpersInterface;

class ModuleHelpersFacade
{
    public static function getInstance(): ModuleHelpersInterface
    {
        /**
         * @var ModuleHelpersInterface
         */
        $service = App::getContainer()->get(ModuleHelpersInterface::class);
        return $service;
    }
}
