<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class DataloadHelperServiceFacade
{
    public static function getInstance(): DataloadHelperServiceInterface
    {
        /**
         * @var DataloadHelperServiceInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(DataloadHelperServiceInterface::class);
        return $service;
    }
}
