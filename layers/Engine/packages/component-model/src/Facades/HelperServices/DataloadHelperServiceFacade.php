<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;

class DataloadHelperServiceFacade
{
    public static function getInstance(): DataloadHelperServiceInterface
    {
        /**
         * @var DataloadHelperServiceInterface
         */
        $service = App::getContainer()->get(DataloadHelperServiceInterface::class);
        return $service;
    }
}
