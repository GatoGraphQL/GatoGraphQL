<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\Facades\HelperServices;

use PoP\Root\App;
use PoPSchema\ExtendedSchemaCommons\HelperServices\ArrayOrJSONObjectTraversionHelperServiceInterface;

class ArrayOrJSONObjectTraversionHelperServiceFacade
{
    public static function getInstance(): ArrayOrJSONObjectTraversionHelperServiceInterface
    {
        /**
         * @var ArrayOrJSONObjectTraversionHelperServiceInterface
         */
        $service = App::getContainer()->get(ArrayOrJSONObjectTraversionHelperServiceInterface::class);
        return $service;
    }
}
