<?php

declare(strict_types=1);

namespace PoP\Root\Facades\Instances;

use PoP\Root\App;
use PoP\Root\Instances\InstanceManagerInterface;

class SystemInstanceManagerFacade
{
    public static function getInstance(): InstanceManagerInterface
    {
        /**
         * @var InstanceManagerInterface
         */
        $service = App::getSystemContainer()->get(InstanceManagerInterface::class);
        return $service;
    }
}
