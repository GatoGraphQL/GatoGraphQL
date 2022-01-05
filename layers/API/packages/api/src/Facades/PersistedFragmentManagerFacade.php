<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\Root\App;
use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;

class PersistedFragmentManagerFacade
{
    public static function getInstance(): PersistedFragmentManagerInterface
    {
        /**
         * @var PersistedFragmentManagerInterface
         */
        $service = App::getContainer()->get(PersistedFragmentManagerInterface::class);
        return $service;
    }
}
