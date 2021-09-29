<?php

declare(strict_types=1);

namespace PoPSchema\Stances\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Stances\TypeAPIs\StanceTypeAPIInterface;

class StanceTypeAPIFacade
{
    public static function getInstance(): StanceTypeAPIInterface
    {
        /**
         * @var StanceTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(StanceTypeAPIInterface::class);
        return $service;
    }
}
