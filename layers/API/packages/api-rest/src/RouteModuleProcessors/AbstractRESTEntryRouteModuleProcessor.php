<?php

declare(strict_types=1);

namespace PoP\RESTAPI\RouteModuleProcessors;

use PoP\ModuleRouting\AbstractEntryRouteModuleProcessor;
use PoP\RESTAPI\DataStructureFormatters\RESTDataStructureFormatter;

abstract class AbstractRESTEntryRouteModuleProcessor extends AbstractEntryRouteModuleProcessor
{
    function __construct(protected RESTDataStructureFormatter $restDataStructureFormatter)
    {
    }
}
