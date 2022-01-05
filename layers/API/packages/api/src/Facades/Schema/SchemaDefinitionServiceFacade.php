<?php

declare(strict_types=1);

namespace PoP\API\Facades\Schema;

use PoP\Engine\App;
use PoP\API\Schema\SchemaDefinitionServiceInterface;

class SchemaDefinitionServiceFacade
{
    public static function getInstance(): SchemaDefinitionServiceInterface
    {
        /**
         * @var SchemaDefinitionServiceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(SchemaDefinitionServiceInterface::class);
        return $service;
    }
}
