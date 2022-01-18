<?php

declare(strict_types=1);

namespace PoPAPI\API\Facades;

use PoP\Root\App;
use PoPAPI\API\Schema\FieldQueryConvertorInterface;

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
