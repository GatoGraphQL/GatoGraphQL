<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ErrorHandling;

use PoP\ComponentModel\ErrorHandling\ErrorServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ErrorServiceFacade
{
    public static function getInstance(): ErrorServiceInterface
    {
        /**
         * @var ErrorServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(ErrorServiceInterface::class);
        return $service;
    }
}
