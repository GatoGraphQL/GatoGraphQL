<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;

class ApplicationStateHelperServiceFacade
{
    public static function getInstance(): ApplicationStateHelperServiceInterface
    {
        /**
         * @var ApplicationStateHelperServiceInterface
         */
        $service = App::getContainer()->get(ApplicationStateHelperServiceInterface::class);
        return $service;
    }
}
