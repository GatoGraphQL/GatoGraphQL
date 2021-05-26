<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Facades\Routing;

use PoPSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class CMSRoutingStateServiceFacade
{
    public static function getInstance(): CMSRoutingStateServiceInterface
    {
        /**
         * @var CMSRoutingStateServiceInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(CMSRoutingStateServiceInterface::class);
        return $service;
    }
}
