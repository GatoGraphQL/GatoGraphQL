<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\TypeSerialization;

use PoP\Root\App;
use PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface;

class TypeSerializationServiceFacade
{
    public static function getInstance(): TypeSerializationServiceInterface
    {
        /**
         * @var TypeSerializationServiceInterface
         */
        $service = App::getContainer()->get(TypeSerializationServiceInterface::class);
        return $service;
    }
}
