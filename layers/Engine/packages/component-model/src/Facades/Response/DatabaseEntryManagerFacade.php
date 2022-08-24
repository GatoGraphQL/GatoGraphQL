<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Response;

use PoP\Root\App;
use PoP\ComponentModel\Response\DatabaseEntryManagerInterface;

class DatabaseEntryManagerFacade
{
    public static function getInstance(): DatabaseEntryManagerInterface
    {
        /**
         * @var DatabaseEntryManagerInterface
         */
        $service = App::getContainer()->get(DatabaseEntryManagerInterface::class);
        return $service;
    }
}
