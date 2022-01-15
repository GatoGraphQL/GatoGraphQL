<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Error;

use PoP\Root\App;
use PoPSchema\SchemaCommons\Error\ErrorManagerInterface;

class ErrorManagerFacade
{
    public static function getInstance(): ErrorManagerInterface
    {
        /**
         * @var ErrorManagerInterface
         */
        $service = App::getContainer()->get(ErrorManagerInterface::class);
        return $service;
    }
}
