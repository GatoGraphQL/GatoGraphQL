<?php

declare(strict_types=1);

namespace PoP\Root\Facades\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\HooksAPIInterface;

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
