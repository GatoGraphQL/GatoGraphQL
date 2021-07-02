<?php
use PoP\Hooks\Facades\HooksAPIFacade;

function gdImagesAttributes()
{

    // Allow the CDN to add 'crossorigin="anonymous"' so that images can be cached in SW
    return HooksAPIFacade::getInstance()->applyFilters('gdImagesAttributes', '');
}
