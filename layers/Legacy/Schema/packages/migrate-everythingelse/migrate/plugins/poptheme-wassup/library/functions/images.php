<?php

function gdLogo($size = 'large')
{
    $gdLogo = \PoP\Root\App::applyFilters('gd_get_logo', array());
    return $gdLogo[$size];
}

function gdImagesBackground()
{
    return \PoP\Root\App::applyFilters('gdImagesBackground', '');
}

function gdImagesWelcome()
{
    return \PoP\Root\App::applyFilters('gdImagesWelcome', '');
}
