<?php

// Add the source param whenever in an author
\PoP\Root\App::getHookManager()->addFilter('PoP_Module_Processor_CustomSubMenus:getUrl:author', 'gdUreAddSourceParamToSubmenu', 10, 3);
function gdUreAddSourceParamToSubmenu($url, $route, $user_id)
{

    // Add for all the content pages: all of them except Description and Members
    $skip = \PoP\Root\App::getHookManager()->applyFilters(
        'gdUreAddSourceParamToSubmenu:skip:routes',
        array(
            POP_ROUTE_DESCRIPTION,
        )
    );
    if (in_array($route, $skip)) {
        return $url;
    }

    return gdUreAddSourceParam($url, $user_id);
}
