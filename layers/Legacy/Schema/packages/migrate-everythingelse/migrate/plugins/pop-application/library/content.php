<?php

use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdGetFavicon()
{
    $cmsService = CMSServiceFacade::getInstance();
    return \PoP\Root\App::getHookManager()->applyFilters(
        'gdGetFavicon',
        $cmsService->getHomeURL() . '/favicon.ico'
    );
}
