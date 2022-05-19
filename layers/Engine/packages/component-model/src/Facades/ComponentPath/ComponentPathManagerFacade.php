<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ComponentPath;

use PoP\Root\App;
use PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface;

class ComponentPathManagerFacade
{
    public static function getInstance(): ComponentPathManagerInterface
    {
        /**
         * @var ComponentPathManagerInterface
         */
        $service = App::getContainer()->get(ComponentPathManagerInterface::class);
        return $service;
    }
}
