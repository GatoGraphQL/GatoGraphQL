<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SemverHelperServiceFacade
{
    public static function getInstance(): SemverHelperServiceInterface
    {
        /**
         * @var SemverHelperServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(SemverHelperServiceInterface::class);
        return $service;
    }
}
