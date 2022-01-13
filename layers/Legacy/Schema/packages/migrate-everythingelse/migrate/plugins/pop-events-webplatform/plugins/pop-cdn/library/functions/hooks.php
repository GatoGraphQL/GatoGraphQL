<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

 
class PoP_Events_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'getThumbprintPartialpaths'),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        if (in_array(
            $thumbprint,
            array(
                POP_CDN_THUMBPRINT_POST,
                POP_CDN_THUMBPRINT_USER
            )
        )
        ) {
                $routes = array(
                POP_EVENTS_ROUTE_EVENTS,
                POP_EVENTS_ROUTE_EVENTSCALENDAR,
                POP_EVENTS_ROUTE_PASTEVENTS,
            );
        }
        if ($routes) {
            foreach ($routes as $route) {
                $paths[] = $route.'/';
            }
        }
        
        return $paths;
    }
}

/**
 * Initialize
 */
new PoP_Events_CDN_Hooks();
