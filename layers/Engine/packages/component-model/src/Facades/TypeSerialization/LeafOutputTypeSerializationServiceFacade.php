<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ObjectSerialization;

use PoP\Root\App;
use PoP\ComponentModel\TypeSerialization\LeafOutputTypeSerializationServiceInterface;

class LeafOutputTypeSerializationServiceFacade
{
    public static function getInstance(): LeafOutputTypeSerializationServiceInterface
    {
        /**
         * @var LeafOutputTypeSerializationServiceInterface
         */
        $service = App::getContainer()->get(LeafOutputTypeSerializationServiceInterface::class);
        return $service;
    }
}
