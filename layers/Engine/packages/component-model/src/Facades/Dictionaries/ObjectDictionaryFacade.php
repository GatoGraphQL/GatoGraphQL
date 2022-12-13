<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Dictionaries;

use PoP\Root\App;
use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;

class ObjectDictionaryFacade
{
    public static function getInstance(): ObjectDictionaryInterface
    {
        /**
         * @var ObjectDictionaryInterface
         */
        $service = App::getContainer()->get(ObjectDictionaryInterface::class);
        return $service;
    }
}
