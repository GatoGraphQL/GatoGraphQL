<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ObjectSerialization;

use PoP\Root\App;
use PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class ObjectSerializationManagerFacade
{
    public static function getInstance(): ObjectSerializationManagerInterface
    {
        /**
         * @var ObjectSerializationManagerInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ObjectSerializationManagerInterface::class);
        return $service;
    }
}
