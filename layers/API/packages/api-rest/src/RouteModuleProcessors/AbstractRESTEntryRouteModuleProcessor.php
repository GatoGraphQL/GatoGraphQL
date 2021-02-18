<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;

abstract class AbstractRESTEntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    protected RESTDataStructureFormatter $restDataStructureFormatter;

    function __construct(RESTDataStructureFormatter $restDataStructureFormatter)
    {
        $this->restDataStructureFormatter = $restDataStructureFormatter;
    }
}
