<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\ErrorHandling;

use PoP\Engine\ErrorHandling\ErrorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ErrorManagerFacade
{
    public static function getInstance(): ErrorManagerInterface
    {
        /**
         * @var ErrorManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(ErrorManagerInterface::class);
        return $service;
    }
}
