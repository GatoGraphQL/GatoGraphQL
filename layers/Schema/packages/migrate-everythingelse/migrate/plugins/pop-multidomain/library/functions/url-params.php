<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'gdJqueryConstantsMultidomain');
function gdJqueryConstantsMultidomain($jqueryConstants)
{
    // Properties for all the domains
    $jqueryConstants['MULTIDOMAIN_WEBSITES'] = PoP_MultiDomain_Utils::getMultidomainWebsites();

    // External page, to load aggregated PoP URLs into the browser
    if (defined('POP_MULTIDOMAIN_ROUTE_EXTERNAL') && POP_MULTIDOMAIN_ROUTE_EXTERNAL) {
        $jqueryConstants['EXTERNAL_URL'] = RouteUtils::getRouteURL(POP_MULTIDOMAIN_ROUTE_EXTERNAL);
    }

    return $jqueryConstants;
}
