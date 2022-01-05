<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\Engine\App;
use PoP\API\PersistedQueries\PersistedQueryManagerInterface;

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
