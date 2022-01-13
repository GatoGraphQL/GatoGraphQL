<?php

\PoP\Root\App::getHookManager()->addFilter('gd_jquery_constants', 'popSsrJqueryConstants');
function popSsrJqueryConstants($jqueryConstants)
{
    $jqueryConstants['USESERVERSIDERENDERING'] = !PoP_SSR_ServerUtils::disableServerSideRendering() ? true : '';
    return $jqueryConstants;
}
