<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaCastingServiceInterface;

class SchemaCastingServiceFacade
{
    public static function getInstance(): SchemaCastingServiceInterface
    {
        /**
         * @var SchemaCastingServiceInterface
         */
        $service = App::getContainer()->get(SchemaCastingServiceInterface::class);
        return $service;
    }
}
