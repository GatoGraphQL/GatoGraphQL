<?php

declare(strict_types=1);

namespace PoPSchema\Stances\Facades;

use PoPSchema\Stances\TypeAPIs\StanceTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

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
