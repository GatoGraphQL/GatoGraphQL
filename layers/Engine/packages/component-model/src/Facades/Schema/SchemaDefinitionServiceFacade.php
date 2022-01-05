<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;

class SchemaDefinitionServiceFacade
{
    public static function getInstance(): SchemaDefinitionServiceInterface
    {
        /**
         * @var SchemaDefinitionServiceInterface
         */
        $service = App::getContainer()->get(SchemaDefinitionServiceInterface::class);
        return $service;
    }
}
