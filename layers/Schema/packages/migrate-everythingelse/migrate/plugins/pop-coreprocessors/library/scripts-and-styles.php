<?php
use PoP\FileStore\Facades\FileRendererFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

function getGooglemapsUrl($add_version = false)
{
    $googlemaps_url = 'https://maps.google.com/maps/api/js';
    if (POP_COREPROCESSORS_APIKEY_GOOGLEMAPS) {
        $googlemaps_url .= '?key='.POP_COREPROCESSORS_APIKEY_GOOGLEMAPS;
    }

    if ($add_version) {
        $version = POP_COREPROCESSORS_VENDORRESOURCESVERSION;
        $googlemaps_url = GeneralUtils::addQueryArgs([
            'ver' => $version, 
        ], $googlemaps_url);
    }

    return $googlemaps_url;
}


/**
 * Logged in classes: they depend on the domain, so they are added through PHP, not in the .css anymore
 */
function getLoggedinDomainStyles($domain)
{
    global $popcore_userloggedinstyles_file;
    foreach ($popcore_userloggedinstyles_file->getFragments() as $fragment) {
        $fragment->setDomain($domain);
    }
    return FileRendererFacade::getInstance()->render($popcore_userloggedinstyles_file);
}
