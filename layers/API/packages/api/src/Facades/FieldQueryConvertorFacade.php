<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\Engine\App;
use PoP\API\Schema\FieldQueryConvertorInterface;

class FieldQueryConvertorFacade
{
    public static function getInstance(): FieldQueryConvertorInterface
    {
        /**
         * @var FieldQueryConvertorInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(FieldQueryConvertorInterface::class);
        return $service;
    }
}
