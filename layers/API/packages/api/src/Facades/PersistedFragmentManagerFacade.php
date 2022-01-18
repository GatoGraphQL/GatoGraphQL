<?php

declare(strict_types=1);

namespace PoPAPI\API\Facades;

use PoP\Root\App;
use PoPAPI\API\PersistedQueries\PersistedFragmentManagerInterface;

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
