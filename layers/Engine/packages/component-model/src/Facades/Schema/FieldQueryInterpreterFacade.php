<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\Root\App;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

class FieldQueryInterpreterFacade
{
    public static function getInstance(): FieldQueryInterpreterInterface
    {
        /**
         * @var FieldQueryInterpreterInterface
         */
        $service = App::getContainer()->get(FieldQueryInterpreterInterface::class);
        return $service;
    }
}
