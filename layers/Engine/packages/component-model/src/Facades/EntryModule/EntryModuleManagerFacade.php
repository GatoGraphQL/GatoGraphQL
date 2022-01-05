<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\EntryModule;

use PoP\Root\App;
use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;

class EntryModuleManagerFacade
{
    public static function getInstance(): EntryModuleManagerInterface
    {
        /**
         * @var EntryModuleManagerInterface
         */
        $service = App::getContainer()->get(EntryModuleManagerInterface::class);
        return $service;
    }
}
