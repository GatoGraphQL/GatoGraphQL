<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Modules;

use PoP\Root\App;
use PoP\ComponentModel\Modules\ComponentHelpersInterface;

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
