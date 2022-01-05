<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\InputCoercingServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class InputCoercingServiceFacade
{
    public static function getInstance(): InputCoercingServiceInterface
    {
        /**
         * @var InputCoercingServiceInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(InputCoercingServiceInterface::class);
        return $service;
    }
}
