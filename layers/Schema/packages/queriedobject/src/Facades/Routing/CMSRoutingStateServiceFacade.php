<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Facades\Routing;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;

class CMSRoutingStateServiceFacade
{
    public static function getInstance(): CMSRoutingStateServiceInterface
    {
        /**
         * @var CMSRoutingStateServiceInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CMSRoutingStateServiceInterface::class);
        return $service;
    }
}
