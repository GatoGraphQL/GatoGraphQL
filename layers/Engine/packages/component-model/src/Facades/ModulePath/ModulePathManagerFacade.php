<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModulePath;

use PoP\ComponentModel\ModulePath\ModulePathManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ModulePathManagerFacade
{
    public static function getInstance(): ModulePathManagerInterface
    {
        /**
         * @var ModulePathManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(ModulePathManagerInterface::class);
        return $service;
    }
}
