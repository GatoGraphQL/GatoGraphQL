<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\Root\App;
use PoP\ComponentModel\Schema\InputCoercingServiceInterface;

class InputCoercingServiceFacade
{
    public static function getInstance(): InputCoercingServiceInterface
    {
        /**
         * @var InputCoercingServiceInterface
         */
        $service = App::getContainer()->get(InputCoercingServiceInterface::class);
        return $service;
    }
}
