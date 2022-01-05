<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\Root\App;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FieldQueryInterpreterFacade
{
    public static function getInstance(): FieldQueryInterpreterInterface
    {
        /**
         * @var FieldQueryInterpreterInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(FieldQueryInterpreterInterface::class);
        return $service;
    }
}
