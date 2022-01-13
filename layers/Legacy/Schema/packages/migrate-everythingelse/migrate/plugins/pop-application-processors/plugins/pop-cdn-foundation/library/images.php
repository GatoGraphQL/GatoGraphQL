<?php

 
\PoP\Root\App::addAction(
    'popcms:init', 
    'initWassupCdnImageAttributes'
);
function initWassupCdnImageAttributes()
{

    // If we have defined the Assets CDN, then we must set the images to use the crossorigin attribute,
    // so they can be cached in SW through CORS configuration
    if (defined('POP_CDNFOUNDATION_CDN_ASSETS_URI') && POP_CDNFOUNDATION_CDN_ASSETS_URI) {
        \PoP\Root\App::addFilter('gdImagesAttributes', 'wassupCdnImageAttributes');
    }
}
function wassupCdnImageAttributes($attributes)
{
    return $attributes.' crossorigin="anonymous"';
}
