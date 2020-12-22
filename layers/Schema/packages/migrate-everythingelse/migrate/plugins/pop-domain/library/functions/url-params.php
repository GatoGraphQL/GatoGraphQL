<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Route\RouteUtils;

define('POP_URLPARAM_DOMAIN', 'domain');
define('POP_URLPARAM_ORIGIN', 'origin');

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsDomain');
function gdJqueryConstantsDomain($jqueryConstants)
{
        $jqueryConstants['URLPARAM_ORIGIN'] = POP_URLPARAM_ORIGIN;

    // Needed to initialize a domain
    $jqueryConstants['PLACEHOLDER_DOMAINURL'] = GeneralUtils::addQueryArgs([
        POP_URLPARAM_DOMAIN => '{0}', 
    ], RouteUtils::getRouteURL(POP_DOMAIN_ROUTE_LOADERS_INITIALIZEDOMAIN));

    return $jqueryConstants;
}
