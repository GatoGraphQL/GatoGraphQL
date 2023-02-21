<?php

declare(strict_types=1);

namespace PoPAPI\API\Facades\HelperServices;

use PoP\Root\App;
use PoPAPI\API\HelperServices\ApplicationStateFillerServiceInterface;

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
