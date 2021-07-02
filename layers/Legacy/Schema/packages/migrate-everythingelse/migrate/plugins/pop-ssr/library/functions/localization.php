<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd_jquery_constants', 'popSsrJqueryConstants');
function popSsrJqueryConstants($jqueryConstants)
{
    $jqueryConstants['USESERVERSIDERENDERING'] = !PoP_SSR_ServerUtils::disableServerSideRendering() ? true : '';
    return $jqueryConstants;
}
