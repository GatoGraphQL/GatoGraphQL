<?php

declare(strict_types=1);

namespace PoPSchema\Locations\Facades;

use PoPSchema\Locations\TypeAPIs\LocationTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class LocationTypeAPIFacade
{
    public static function getInstance(): LocationTypeAPIInterface
    {
        /**
         * @var LocationTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(LocationTypeAPIInterface::class);
        return $service;
    }
}
