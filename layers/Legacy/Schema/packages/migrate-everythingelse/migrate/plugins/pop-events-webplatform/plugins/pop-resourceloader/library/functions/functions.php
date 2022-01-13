<?php
use PoPSchema\Events\Facades\EventTypeAPIFacade;

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

// Add the Events and Locations for the resourceLoader single path configuration
\PoP\Root\App::getHookManager()->addFilter('PoP_ResourceLoader_FileReproduction_Config:configuration:category-paths', 'emPopResourceloaderSinglePaths');
function emPopResourceloaderSinglePaths($paths)
{
    $eventTypeAPI = EventTypeAPIFacade::getInstance();
    $paths[] = $eventTypeAPI->getEventCustomPostTypeSlug() . '/';
    return $paths;
}
