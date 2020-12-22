<?php
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Add the Events and Locations for the resourceLoader single path configuration
HooksAPIFacade::getInstance()->addFilter('PoP_ResourceLoader_FileReproduction_Config:configuration:category-paths', 'popLocationsResourceloaderSinglePaths');
function popLocationsResourceloaderSinglePaths($paths)
{
    $pluginapi = PoP_Locations_APIFactory::getInstance();
    $paths[] = $pluginapi->getLocationPostTypeSlug().'/';
    return $paths;
}
