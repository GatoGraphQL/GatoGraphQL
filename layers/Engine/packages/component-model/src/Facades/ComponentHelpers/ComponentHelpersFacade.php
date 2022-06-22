<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ComponentHelpers;

use PoP\Root\App;
use PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface;

class ComponentHelpersFacade
{
    public static function getInstance(): ComponentHelpersInterface
    {
        /**
         * @var ComponentHelpersInterface
         */
        $service = App::getContainer()->get(ComponentHelpersInterface::class);
        return $service;
    }
}
