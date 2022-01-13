<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popUserloginJqueryConstants');
function popUserloginJqueryConstants($jqueryConstants)
{

    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    // Comment Leo 04/08/2017: the original idea was to allow it to fetch the initial data from many domains
    // However the problem then is that we needed to initialize the loggedin styles for that domain (`wassup_loggedin_styles`)
    // yet, the domain would still not be initialized on the webplatform, so when initializing it, it would execute this logic once again,
    // and print the same styles once again. Not nice, so commented it out.
    // // Can fetch many pages, from different domains
    // $loggedinuserdata_urls = PoP_Module_Processor_UserAccountUtils::get_loggedinuserdata_urls();
    $loggedinuserdata_urls = array(RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA));
    $jqueryConstants['USERLOGGEDIN_DATA_PAGEURLS'] = $loggedinuserdata_urls;
    
    return $jqueryConstants;
}
