<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ComponentFiltering;

use PoP\Root\App;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;

class ComponentFilterManagerFacade
{
    public static function getInstance(): ComponentFilterManagerInterface
    {
        /**
         * @var ComponentFilterManagerInterface
         */
        $service = App::getContainer()->get(ComponentFilterManagerInterface::class);
        return $service;
    }
}
