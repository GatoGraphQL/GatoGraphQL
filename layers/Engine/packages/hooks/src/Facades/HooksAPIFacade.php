<?php

declare(strict_types=1);

namespace PoP\Hooks\Facades;

use PoP\Root\App;
use PoP\Hooks\HooksAPIInterface;

class HooksAPIFacade
{
    public static function getInstance(): HooksAPIInterface
    {
        /**
         * @var HooksAPIInterface
         */
        $service = App::getContainer()->get(HooksAPIInterface::class);
        return $service;
    }
}
