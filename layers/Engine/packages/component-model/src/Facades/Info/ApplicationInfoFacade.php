<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Info;

use PoP\Root\App;
use PoP\ComponentModel\Info\ApplicationInfoInterface;

class ApplicationInfoFacade
{
    public static function getInstance(): ApplicationInfoInterface
    {
        /**
         * @var ApplicationInfoInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(ApplicationInfoInterface::class);
        return $service;
    }
}
