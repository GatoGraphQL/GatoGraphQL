<?php

use PoP\Engine\Facades\CMS\CMSServiceFacade;

function gdGetFavicon()
{
    $cmsService = CMSServiceFacade::getInstance();
    return \PoP\Root\App::getHookManager()->applyFilters(
        'gdGetFavicon',
        $cmsService->getHomeURL() . '/favicon.ico'
    );
}
