<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SemverHelperServiceFacade
{
    public static function getInstance(): SemverHelperServiceInterface
    {
        /**
         * @var SemverHelperServiceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(SemverHelperServiceInterface::class);
        return $service;
    }
}
