<?php

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;

class PoP_CDN_Thumbprint_Utils
{
    public static function convertUrl($url, $thumbprints = array())
    {

        // Convert the URL to point to the CDN domain and use the passed thumbprints
        global $pop_cdn_thumbprint_manager;
        $cmsService = CMSServiceFacade::getInstance();
        
        // Plug-in the CDN here, and the needed params
        $homeurl = $cmsService->getSiteURL();
        if (POP_CDNFOUNDATION_CDN_CONTENT_URI && substr($url, 0, strlen($homeurl)) == $homeurl) {
            // Replace the home url with the CDN domain
            $url = POP_CDNFOUNDATION_CDN_CONTENT_URI.substr($url, strlen($homeurl));

            // Add the version
            // $vars = ApplicationState::getVars();
            // $url = GeneralUtils::addQueryArgs([POP_CDN_URLPARAM_VERSION => ApplicationInfoFacade::getInstance()->getVersion()], $url);

            // Add the thumbprints
            $thumbprints_value = array();
            foreach ($thumbprints as $thumbprint) {
                $thumbprints_value[] = $pop_cdn_thumbprint_manager->getThumbprintValue($thumbprint);
            }
            $url = GeneralUtils::addQueryArgs([
                GD_URLPARAM_CDNTHUMBPRINT => implode(POP_CDN_SEPARATOR_THUMBPRINT, $thumbprints_value), 
            ], $url);
        }

        return $url;
    }
}
