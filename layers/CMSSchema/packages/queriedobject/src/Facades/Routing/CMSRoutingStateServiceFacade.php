<?php

declare(strict_types=1);

namespace PoPCMSSchema\QueriedObject\Facades\Routing;

use PoP\Root\App;
use PoPCMSSchema\QueriedObject\Routing\CMSRoutingStateServiceInterface;

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
