<?php

use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdGetFavicon()
{
    $cmsService = CMSServiceFacade::getInstance();
    return HooksAPIFacade::getInstance()->applyFilters(
        'gdGetFavicon',
        $cmsService->getHomeURL() . '/favicon.ico'
    );
}
