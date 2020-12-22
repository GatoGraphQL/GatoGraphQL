<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\API\PersistedQueries\PersistedQueryManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedQueryManagerFacade
{
    public static function getInstance(): PersistedQueryManagerInterface
    {
        /**
         * @var PersistedQueryManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PersistedQueryManagerInterface::class);
        return $service;
    }
}
