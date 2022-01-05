<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Error;

use PoP\Root\App;
use PoP\ComponentModel\Error\ErrorProviderInterface;

class ErrorProviderFacade
{
    public static function getInstance(): ErrorProviderInterface
    {
        /**
         * @var ErrorProviderInterface
         */
        $service = App::getContainer()->get(ErrorProviderInterface::class);
        return $service;
    }
}
