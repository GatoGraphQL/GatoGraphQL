<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Error;

use PoP\Engine\App;
use PoP\Engine\Error\ErrorManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ErrorManagerFacade
{
    public static function getInstance(): ErrorManagerInterface
    {
        /**
         * @var ErrorManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ErrorManagerInterface::class);
        return $service;
    }
}
