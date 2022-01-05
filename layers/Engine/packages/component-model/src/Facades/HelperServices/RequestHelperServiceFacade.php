<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class RequestHelperServiceFacade
{
    public static function getInstance(): RequestHelperServiceInterface
    {
        /**
         * @var RequestHelperServiceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(RequestHelperServiceInterface::class);
        return $service;
    }
}
