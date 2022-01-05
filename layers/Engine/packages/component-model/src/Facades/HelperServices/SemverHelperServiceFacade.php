<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;

class SemverHelperServiceFacade
{
    public static function getInstance(): SemverHelperServiceInterface
    {
        /**
         * @var SemverHelperServiceInterface
         */
        $service = App::getContainer()->get(SemverHelperServiceInterface::class);
        return $service;
    }
}
