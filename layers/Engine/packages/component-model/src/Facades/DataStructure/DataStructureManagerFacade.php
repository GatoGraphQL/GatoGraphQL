<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\DataStructure;

use PoP\Root\App;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;

class DataStructureManagerFacade
{
    public static function getInstance(): DataStructureManagerInterface
    {
        /**
         * @var DataStructureManagerInterface
         */
        $service = App::getContainer()->get(DataStructureManagerInterface::class);
        return $service;
    }
}
