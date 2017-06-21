<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Redirects functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Taken from http://wordpress.stackexchange.com/questions/3326/301-redirect-instead-of-404-when-url-is-a-prefix-of-a-post-or-page-name
// Needed because calling https://www.mesym.com/events/upcoming/ was redirecting to another URL, eg: https://www.mesym.com/newsletters/upcoming-events-in-august/
add_filter('redirect_canonical', 'no_redirect_on_404');
function no_redirect_on_404($redirect_url)
{
	$vars = GD_TemplateManager_Utils::get_vars();
    if ($vars['global-state']['is-404']/*is_404()*/) {
        return false;
    }
    return $redirect_url;
}