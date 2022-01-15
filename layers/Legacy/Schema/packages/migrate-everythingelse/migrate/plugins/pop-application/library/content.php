<?php

use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

function gdGetFavicon()
{
    $cmsService = CMSServiceFacade::getInstance();
    return \PoP\Root\App::applyFilters(
        'gdGetFavicon',
        $cmsService->getHomeURL() . '/favicon.ico'
    );
}
