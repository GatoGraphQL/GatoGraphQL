<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\ErrorHandling;

use PoP\Engine\ErrorHandling\ErrorHelperInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ErrorHelperFacade
{
    public static function getInstance(): ErrorHelperInterface
    {
        /**
         * @var ErrorHelperInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(ErrorHelperInterface::class);
        return $service;
    }
}
