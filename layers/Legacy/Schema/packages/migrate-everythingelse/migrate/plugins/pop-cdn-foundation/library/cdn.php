<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

HooksAPIFacade::getInstance()->addAction(
    'popcms:init', 
    function () {
        // Use the assets url instead of the site url for all the scripts and styles
        $cmsService = CMSServiceFacade::getInstance();
        if (POP_CDNFOUNDATION_CDN_ASSETS_URI && (POP_CDNFOUNDATION_CDN_ASSETS_URI != $cmsService->getSiteURL())) {
            HooksAPIFacade::getInstance()->addFilter('popcms:styleSrc', 'popCdnfoundationAssetsrc');
            HooksAPIFacade::getInstance()->addFilter('popcms:scriptSrc', 'popCdnfoundationAssetsrc');
        }
    },
    11000
);
function popCdnfoundationAssetsrc($src)
{
    // Replace the home with the CDN URL
    $cmsService = CMSServiceFacade::getInstance();
    $home = $cmsService->getSiteURL();
    if (substr($src, 0, strlen($home)) == $cmsService->getSiteURL()) {
        return POP_CDNFOUNDATION_CDN_ASSETS_URI.substr($src, strlen($home));
    }
    return $src;
}
