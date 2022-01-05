<?php

declare(strict_types=1);

namespace PoP\Engine\Facades\Schema;

use PoP\Engine\Schema\SchemaDefinitionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SchemaDefinitionServiceFacade
{
    public static function getInstance(): SchemaDefinitionServiceInterface
    {
        /**
         * @var SchemaDefinitionServiceInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(SchemaDefinitionServiceInterface::class);
        return $service;
    }
}
