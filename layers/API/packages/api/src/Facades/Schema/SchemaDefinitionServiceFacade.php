<?php

declare(strict_types=1);

namespace PoPAPI\API\Facades\Schema;

use PoP\Root\App;
use PoPAPI\API\Schema\SchemaDefinitionServiceInterface;

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
