<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    function () {
        // Use the assets url instead of the site url for all the scripts and styles
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        if (POP_CDNFOUNDATION_CDN_ASSETS_URI && (POP_CDNFOUNDATION_CDN_ASSETS_URI != $cmsengineapi->getSiteURL())) {
            HooksAPIFacade::getInstance()->addFilter('popcms:styleSrc', 'popCdnfoundationAssetsrc');
            HooksAPIFacade::getInstance()->addFilter('popcms:scriptSrc', 'popCdnfoundationAssetsrc');
        }
    },
    11000
);
function popCdnfoundationAssetsrc($src)
{
    // Replace the home with the CDN URL
    $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
    $home = $cmsengineapi->getSiteURL();
    if (substr($src, 0, strlen($home)) == $cmsengineapi->getSiteURL()) {
        return POP_CDNFOUNDATION_CDN_ASSETS_URI.substr($src, strlen($home));
    }
    return $src;
}
