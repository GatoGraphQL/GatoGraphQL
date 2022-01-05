<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModuleProcessors;

use PoP\Root\App;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;

class ModuleProcessorManagerFacade
{
    public static function getInstance(): ModuleProcessorManagerInterface
    {
        /**
         * @var ModuleProcessorManagerInterface
         */
        $service = App::getContainer()->get(ModuleProcessorManagerInterface::class);
        return $service;
    }
}
