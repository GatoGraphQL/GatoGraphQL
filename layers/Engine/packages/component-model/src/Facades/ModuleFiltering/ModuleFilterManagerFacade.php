<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModuleFiltering;

use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ModuleFilterManagerFacade
{
    public static function getInstance(): ModuleFilterManagerInterface
    {
        /**
         * @var ModuleFilterManagerInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(ModuleFilterManagerInterface::class);
        return $service;
    }
}
