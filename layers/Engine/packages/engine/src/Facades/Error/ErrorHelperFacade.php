<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Error;

use PoP\Engine\Error\ErrorHelperInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ErrorHelperFacade
{
    public static function getInstance(): ErrorHelperInterface
    {
        /**
         * @var ErrorHelperInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(ErrorHelperInterface::class);
        return $service;
    }
}
