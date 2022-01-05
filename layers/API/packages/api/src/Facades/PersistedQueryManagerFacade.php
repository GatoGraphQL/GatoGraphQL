<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\Engine\App;
use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryManagerFacade
{
    public static function getInstance(): PersistedQueryManagerInterface
    {
        /**
         * @var PersistedQueryManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PersistedQueryManagerInterface::class);
        return $service;
    }
}
