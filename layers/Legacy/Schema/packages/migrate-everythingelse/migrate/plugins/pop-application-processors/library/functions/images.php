<?php

function gdImagesAttributes()
{

    // Allow the CDN to add 'crossorigin="anonymous"' so that images can be cached in SW
    return \PoP\Root\App::getHookManager()->applyFilters('gdImagesAttributes', '');
}
