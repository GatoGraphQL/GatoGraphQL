<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\DataStructure;

use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class DataStructureManagerFacade
{
    public static function getInstance(): DataStructureManagerInterface
    {
        /**
         * @var DataStructureManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(DataStructureManagerInterface::class);
        return $service;
    }
}
