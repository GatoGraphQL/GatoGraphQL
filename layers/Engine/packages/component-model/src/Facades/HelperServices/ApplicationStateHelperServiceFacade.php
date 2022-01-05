<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ApplicationStateHelperServiceFacade
{
    public static function getInstance(): ApplicationStateHelperServiceInterface
    {
        /**
         * @var ApplicationStateHelperServiceInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(ApplicationStateHelperServiceInterface::class);
        return $service;
    }
}
