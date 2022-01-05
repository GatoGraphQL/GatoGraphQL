<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\EntryModule;

use PoP\Root\App;
use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EntryModuleManagerFacade
{
    public static function getInstance(): EntryModuleManagerInterface
    {
        /**
         * @var EntryModuleManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(EntryModuleManagerInterface::class);
        return $service;
    }
}
