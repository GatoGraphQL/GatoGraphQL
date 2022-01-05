<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\EntryModule;

use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class EntryModuleManagerFacade
{
    public static function getInstance(): EntryModuleManagerInterface
    {
        /**
         * @var EntryModuleManagerInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(EntryModuleManagerInterface::class);
        return $service;
    }
}
