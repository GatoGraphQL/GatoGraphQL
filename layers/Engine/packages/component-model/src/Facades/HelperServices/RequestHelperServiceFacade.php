<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class RequestHelperServiceFacade
{
    public static function getInstance(): RequestHelperServiceInterface
    {
        /**
         * @var RequestHelperServiceInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(RequestHelperServiceInterface::class);
        return $service;
    }
}
