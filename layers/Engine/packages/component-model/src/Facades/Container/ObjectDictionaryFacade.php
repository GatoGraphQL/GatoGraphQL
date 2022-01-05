<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Container;

use PoP\Root\App;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;

class ObjectDictionaryFacade
{
    public static function getInstance(): ObjectDictionaryInterface
    {
        /**
         * @var ObjectDictionaryInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ObjectDictionaryInterface::class);
        return $service;
    }
}
