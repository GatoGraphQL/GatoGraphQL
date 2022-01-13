<?php
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * login.php
 */
\PoP\Root\App::getHookManager()->addFilter('gdGetLoginHtml', 'gdUreGetLoginHtml', 10, 2);
function gdUreGetLoginHtml($html, $capitalize = false)
{
    $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
    $li = '<li role="presentation"><a href="%s">%s</a></li>';
    return
    sprintf(
        '<span class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">%s<span class="caret"></span></a>%s</span>',
        $capitalize ? TranslationAPIFacade::getInstance()->__('Log in', 'ure-pop') : TranslationAPIFacade::getInstance()->__('log in', 'ure-pop'),
        sprintf(
            '<ul class="dropdown-menu login" role="menu">%s%s%s</ul>',
            sprintf(
                $li,
                $cmsuseraccountapi->getLoginURL(),
                RouteUtils::getRouteTitle(POP_USERLOGIN_ROUTE_LOGIN)
            ),
            sprintf(
                $li,
                RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION),
                RouteUtils::getRouteTitle(POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION)
            ),
            sprintf(
                $li,
                RouteUtils::getRouteURL(POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL),
                RouteUtils::getRouteTitle(POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL)
            )
        )
    );
}
