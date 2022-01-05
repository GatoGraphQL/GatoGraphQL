<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\ModelInstance;

use PoP\Root\App;
use PoP\ComponentModel\ModelInstance\ModelInstanceInterface;

class ModelInstanceFacade
{
    public static function getInstance(): ModelInstanceInterface
    {
        /**
         * @var ModelInstanceInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ModelInstanceInterface::class);
        return $service;
    }
}
