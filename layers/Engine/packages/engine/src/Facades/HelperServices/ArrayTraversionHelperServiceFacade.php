<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Schema;

use PoP\Root\App;
use PoP\Engine\HelperServices\ArrayTraversionHelperServiceInterface;

class ArrayTraversionHelperServiceFacade
{
    public static function getInstance(): ArrayTraversionHelperServiceInterface
    {
        /**
         * @var ArrayTraversionHelperServiceInterface
         */
        $service = App::getContainer()->get(ArrayTraversionHelperServiceInterface::class);
        return $service;
    }
}
