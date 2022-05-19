<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ComponentPath;

use PoP\Root\App;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;

class ComponentPathHelpersFacade
{
    public static function getInstance(): ComponentPathHelpersInterface
    {
        /**
         * @var ComponentPathHelpersInterface
         */
        $service = App::getContainer()->get(ComponentPathHelpersInterface::class);
        return $service;
    }
}
