<?php

declare(strict_types=1);

namespace PoPAPI\API\Facades;

use PoP\Root\App;
use PoPAPI\API\PersistedQueries\PersistedQueryManagerInterface;

class PersistedQueryManagerFacade
{
    public static function getInstance(): PersistedQueryManagerInterface
    {
        /**
         * @var PersistedQueryManagerInterface
         */
        $service = App::getContainer()->get(PersistedQueryManagerInterface::class);
        return $service;
    }
}
