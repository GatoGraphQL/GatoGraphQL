<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popWebPlatformengineoptimizationsJqueryConstants');
function popWebPlatformengineoptimizationsJqueryConstants($jqueryConstants)
{
    $jqueryConstants['RUNTIMEJS'] = PoP_WebPlatformEngineOptimizations_ServerUtils::extractResponseIntoJsfilesOnRuntime() ? true : '';

    return $jqueryConstants;
}
