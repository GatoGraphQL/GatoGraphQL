<?php

\PoP\Root\App::addFilter('gd_jquery_constants', 'popWebPlatformengineoptimizationsJqueryConstants');
function popWebPlatformengineoptimizationsJqueryConstants($jqueryConstants)
{
    $jqueryConstants['RUNTIMEJS'] = PoP_WebPlatformEngineOptimizations_ServerUtils::extractResponseIntoJsfilesOnRuntime() ? true : '';

    return $jqueryConstants;
}
