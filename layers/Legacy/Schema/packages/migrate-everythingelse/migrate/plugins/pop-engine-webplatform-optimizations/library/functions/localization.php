<?php

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'popWebPlatformengineoptimizationsJqueryConstants');
function popWebPlatformengineoptimizationsJqueryConstants($jqueryConstants)
{
    $jqueryConstants['RUNTIMEJS'] = PoP_WebPlatformEngineOptimizations_ServerUtils::extractResponseIntoJsfilesOnRuntime() ? true : '';

    return $jqueryConstants;
}
