<?php

declare(strict_types=1);

namespace PoP\API\Facades;

use PoP\API\Schema\FieldQueryConvertorInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FieldQueryConvertorFacade
{
    public static function getInstance(): FieldQueryConvertorInterface
    {
        /**
         * @var FieldQueryConvertorInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(FieldQueryConvertorInterface::class);
        return $service;
    }
}
