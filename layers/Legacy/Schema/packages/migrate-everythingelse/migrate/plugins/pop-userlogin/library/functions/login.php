<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Route\RouteUtils;

function gdGetLoginHtml($capitalize = false)
{
    $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
    $title = RouteUtils::getRouteTitle(POP_USERLOGIN_ROUTE_LOGIN);
    $html = sprintf(
        '<a href="%s">%s</a>',
        $cmsuseraccountapi->getLoginURL(),
        $capitalize ? $title : strtolower($title)
    );
    return \PoP\Root\App::getHookManager()->applyFilters('gdGetLoginHtml', $html, $capitalize);
}

// The Theme must set this value as true, so that it fails loading, it doesn't block the user from logging into the website through wp-login.php
function enableLoginApplicationScreens()
{
    return \PoP\Root\App::getHookManager()->applyFilters('enableLoginApplicationScreens', false);
}

/**
 * Remove access to wp-login.php
 */
\PoP\Root\App::getHookManager()->addAction('pop_wp_login', 'gdWpLoginRedirect');
function gdWpLoginRedirect()
{
    if (enableLoginApplicationScreens()) {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        $cmsengineapi->redirect($cmsuseraccountapi->getLoginURL());
        exit;
    }
}

/**
 * Override default login URL
 */
\PoP\Root\App::getHookManager()->addFilter('popcms:loginUrl', 'gdLoginUrl', 1000, 2);
function gdLoginUrl($login_url, $redirect = '')
{
    if (enableLoginApplicationScreens()) {
        $login_url = RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOGIN);

        // Comment Leo 14/02/2014: This is commented because this links appears above Template, so the "redirect_to"
        // will never be updated when loading pages inside the Template
        // Instead we get the redirect_to from the referrer (check library/template-manager/dataload/queryhandlers/login.php)
        // if ( !empty($redirect) )
        //     $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
    }
    
    return $login_url;
}

\PoP\Root\App::getHookManager()->addFilter('popcms:lostpasswordUrl', 'gdLostpasswordUrl', 1000, 2);
function gdLostpasswordUrl($lostpassword_url, $redirect = '')
{
    if (enableLoginApplicationScreens()) {
        $lostpassword_url = RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOSTPWD);

        // Commented since we're not doing anything with 'redirect_to'. Re-enable once this logic is taken care of
        // if (!empty($redirect)) {
        //     //     $lostpassword_url = GeneralUtils::addQueryArgs([
        //         'redirect_to' => urlencode($redirect), 
        //     ], $lostpassword_url);
        // }
    }
    
    return $lostpassword_url;
}

\PoP\Root\App::getHookManager()->addFilter('popcms:logoutUrl', 'gdLogoutUrl', 1000, 2);
function gdLogoutUrl($logout_url, $redirect = '')
{
    $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
    if (!$cmsapplicationapi->isAdminPanel()) {
        if (enableLoginApplicationScreens()) {
            $logout_url = RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOGOUT);
        
            // Comment Leo 14/02/2014: This is commented because this links appears above Template, so the "redirect_to"
            // will never be updated when loading pages inside the Template
            // Instead we get the redirect_to from the referrer (check library/template-manager/dataload/queryhandlers/login.php)
            // if ( !empty($redirect) )
            //     $logout_url = add_query_arg('redirect_to', urlencode($redirect), $logout_url);
        }
    }
    
    return $logout_url;
}
