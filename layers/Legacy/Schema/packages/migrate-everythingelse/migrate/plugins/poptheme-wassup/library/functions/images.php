<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

function gdLogo($size = 'large')
{
    $gdLogo = \PoP\Root\App::getHookManager()->applyFilters('gd_get_logo', array());
    return $gdLogo[$size];
}

function gdImagesBackground()
{
    return \PoP\Root\App::getHookManager()->applyFilters('gdImagesBackground', '');
}

function gdImagesWelcome()
{
    return \PoP\Root\App::getHookManager()->applyFilters('gdImagesWelcome', '');
}
