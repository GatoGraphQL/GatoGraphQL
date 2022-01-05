<?php

declare(strict_types=1);

namespace PoPSchema\QueriedObject\Facades\Routing;

use PoP\Engine\App;
use PoPSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;

class CMSRoutingStateServiceFacade
{
    public static function getInstance(): CMSRoutingStateServiceInterface
    {
        /**
         * @var CMSRoutingStateServiceInterface
         */
        $service = App::getContainer()->get(CMSRoutingStateServiceInterface::class);
        return $service;
    }
}
