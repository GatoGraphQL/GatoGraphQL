<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\EntryComponent;

use PoP\Root\App;
use PoP\ComponentModel\EntryComponent\EntryComponentManagerInterface;

class EntryComponentManagerFacade
{
    public static function getInstance(): EntryComponentManagerInterface
    {
        /**
         * @var EntryComponentManagerInterface
         */
        $service = App::getContainer()->get(EntryComponentManagerInterface::class);
        return $service;
    }
}
