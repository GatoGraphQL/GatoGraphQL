<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

// Taken from http://wordpress.stackexchange.com/questions/3326/301-redirect-instead-of-404-when-url-is-a-prefix-of-a-post-or-page-name
// Needed because calling https://www.mesym.com/events/upcoming/ was redirecting to another URL, eg: https://www.mesym.com/newsletters/upcoming-events-in-august/
HooksAPIFacade::getInstance()->addFilter('redirect_canonical', 'noRedirectOn404');
function noRedirectOn404($redirect_url)
{
    $vars = ApplicationState::getVars();
    if ($vars['routing']['is-404']) {
        return false;
    }
    return $redirect_url;
}
