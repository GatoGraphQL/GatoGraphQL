<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SchemaDefinitionServiceFacade
{
    public static function getInstance(): SchemaDefinitionServiceInterface
    {
        /**
         * @var SchemaDefinitionServiceInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(SchemaDefinitionServiceInterface::class);
        return $service;
    }
}
