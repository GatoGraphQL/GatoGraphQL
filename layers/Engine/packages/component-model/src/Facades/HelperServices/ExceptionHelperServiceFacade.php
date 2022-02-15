<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\ExceptionHelperServiceInterface;

class ExceptionHelperServiceFacade
{
    public static function getInstance(): ExceptionHelperServiceInterface
    {
        /**
         * @var ExceptionHelperServiceInterface
         */
        $service = App::getContainer()->get(ExceptionHelperServiceInterface::class);
        return $service;
    }
}
