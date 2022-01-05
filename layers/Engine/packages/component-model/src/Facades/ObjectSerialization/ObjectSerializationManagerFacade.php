<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ObjectSerialization;

use PoP\Root\App;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;

class ObjectSerializationManagerFacade
{
    public static function getInstance(): ObjectSerializationManagerInterface
    {
        /**
         * @var ObjectSerializationManagerInterface
         */
        $service = App::getContainer()->get(ObjectSerializationManagerInterface::class);
        return $service;
    }
}
