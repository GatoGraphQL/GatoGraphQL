<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\TypeCastingExecuterInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TypeCastingExecuterFacade
{
    public static function getInstance(): TypeCastingExecuterInterface
    {
        /**
         * @var TypeCastingExecuterInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(TypeCastingExecuterInterface::class);
        return $service;
    }
}
