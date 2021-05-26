<?php

use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

function gdGetFavicon()
{
    $cmsService = CMSServiceFacade::getInstance();
    return HooksAPIFacade::getInstance()->applyFilters(
        'gdGetFavicon',
        $cmsService->getHomeURL() . '/favicon.ico'
    );
}
