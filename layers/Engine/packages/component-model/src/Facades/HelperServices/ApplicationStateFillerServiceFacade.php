<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\ApplicationStateFillerServiceInterface;

class ApplicationStateFillerServiceFacade
{
    public static function getInstance(): ApplicationStateFillerServiceInterface
    {
        /**
         * @var ApplicationStateFillerServiceInterface
         */
        $service = App::getContainer()->get(ApplicationStateFillerServiceInterface::class);
        return $service;
    }
}
