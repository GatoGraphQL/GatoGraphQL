<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popServiceworkersJqueryConstants');
function popServiceworkersJqueryConstants($jqueryConstants)
{
    $jqueryConstants['USE_SW'] = !PoP_ServiceWorkers_ServerUtils::disableServiceworkers() ? true : '';
    return $jqueryConstants;
}
