<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdLogo($size = 'large')
{
    $gdLogo = HooksAPIFacade::getInstance()->applyFilters('gd_get_logo', array());
    return $gdLogo[$size];
}

function gdImagesBackground()
{
    return HooksAPIFacade::getInstance()->applyFilters('gdImagesBackground', '');
}

function gdImagesWelcome()
{
    return HooksAPIFacade::getInstance()->applyFilters('gdImagesWelcome', '');
}
