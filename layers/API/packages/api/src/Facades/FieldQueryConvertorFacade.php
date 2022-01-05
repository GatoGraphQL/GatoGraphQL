<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\Root\App;
use PoP\API\Schema\FieldQueryConvertorInterface;

class FieldQueryConvertorFacade
{
    public static function getInstance(): FieldQueryConvertorInterface
    {
        /**
         * @var FieldQueryConvertorInterface
         */
        $service = App::getContainer()->get(FieldQueryConvertorInterface::class);
        return $service;
    }
}
