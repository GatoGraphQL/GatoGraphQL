<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SchemaNamespacingServiceFacade
{
    public static function getInstance(): SchemaNamespacingServiceInterface
    {
        /**
         * @var SchemaNamespacingServiceInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(SchemaNamespacingServiceInterface::class);
        return $service;
    }
}
