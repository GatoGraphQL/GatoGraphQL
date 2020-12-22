<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class PersistedFragmentManagerFacade
{
    public static function getInstance(): PersistedFragmentManagerInterface
    {
        /**
         * @var PersistedFragmentManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(PersistedFragmentManagerInterface::class);
        return $service;
    }
}
