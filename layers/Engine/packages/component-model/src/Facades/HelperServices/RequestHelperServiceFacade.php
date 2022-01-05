<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;

class RequestHelperServiceFacade
{
    public static function getInstance(): RequestHelperServiceInterface
    {
        /**
         * @var RequestHelperServiceInterface
         */
        $service = App::getContainer()->get(RequestHelperServiceInterface::class);
        return $service;
    }
}
