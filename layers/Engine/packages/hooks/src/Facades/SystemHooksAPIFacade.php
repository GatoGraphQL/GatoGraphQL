<?php

declare(strict_types=1);

namespace PoP\Hooks\Facades;

use PoP\Root\App;
use PoP\Hooks\HooksAPIInterface;

class SystemHooksAPIFacade
{
    public static function getInstance(): HooksAPIInterface
    {
        /**
         * @var HooksAPIInterface
         */
        $service = App::getSystemContainer()->get(HooksAPIInterface::class);
        return $service;
    }
}
