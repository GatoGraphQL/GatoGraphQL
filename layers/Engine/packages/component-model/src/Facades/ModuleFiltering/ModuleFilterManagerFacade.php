<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModuleFiltering;

use PoP\Root\App;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;

class ModuleFilterManagerFacade
{
    public static function getInstance(): ModuleFilterManagerInterface
    {
        /**
         * @var ModuleFilterManagerInterface
         */
        $service = App::getContainer()->get(ModuleFilterManagerInterface::class);
        return $service;
    }
}
