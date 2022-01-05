<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Error;

use PoP\Root\App;
use PoP\ComponentModel\Error\ErrorServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ErrorServiceFacade
{
    public static function getInstance(): ErrorServiceInterface
    {
        /**
         * @var ErrorServiceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ErrorServiceInterface::class);
        return $service;
    }
}
