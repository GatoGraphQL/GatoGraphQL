<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Schema;

use PoP\Engine\App;
use PoP\Engine\Schema\SchemaDefinitionServiceInterface;

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
