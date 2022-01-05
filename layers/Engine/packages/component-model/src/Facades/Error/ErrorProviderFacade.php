<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Error;

use PoP\Root\App;
use PoP\ComponentModel\Error\ErrorProviderInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ErrorProviderFacade
{
    public static function getInstance(): ErrorProviderInterface
    {
        /**
         * @var ErrorProviderInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ErrorProviderInterface::class);
        return $service;
    }
}
