<?php
use PoP\Root\Routing\Routes as RoutingRoutes;

/**
Temporary fix to address the following issue:
ComponentRoutingProcessors winner module from function `getRoutingComponentByMostAllMatchingStateProperties`:
    How to incorporate the CDN hooks about it?
It cannot be done manually, since we don't know which module will be the winner
    And adding hooks here and there depending on the winner is a mess
Solution: do the process automatic, similar to generating the resources.js!
    Can inspect the required dataloaders (independently of if they produce data or empty results)
        From them, get the db_key
        Make a match 1:1 between db_key and CDN Thumbprint (eg: POP_CDN_THUMBPRINT_POST)
        Then, iterating through all the pages and configurations (same as resourceloader!), we are able to know the required CDN thumbprints!
Because the process of iterating through all URLs/module configurations is now used by 2 plugins (pop-resourceloader and pop-cdn), externalize functionality into pop-engine/
After making the process automatic, we can delete this file.
 */
 
class PoP_Wassup_CDN_TemporaryFixHooks
{
    public function __construct()
    {

        // Temporary fix until coding the cdnthumbprints.js file automatically, to also include the winner module from the ComponentRoutingProcessor (in this case, RoutingRoutes::$MAIN for the homepage)
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            $this->getThumbprintPartialpaths(...),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {

        // Temporary fix until coding the cdnthumbprints.js file automatically, to also include the winner module from the ComponentRoutingProcessor
        // In this case, RoutingRoutes::$MAIN is the page for the homepage, for which there are pop-blog/ modules to be inserted (HOMECONTENT etc)
        $routes = array();
        if ($thumbprint == POP_CDN_THUMBPRINT_USER) {
            $routes = array_filter(
                array(
                    RoutingRoutes::$MAIN,
                )
            );
        } elseif ($thumbprint == POP_CDN_THUMBPRINT_POST) {
            $routes = array_filter(
                array(
                    RoutingRoutes::$MAIN,
                )
            );
        }

        // Add the values to the configuration
        foreach ($routes as $route) {
            $paths[] = $route.'/';
        }
        
        return $paths;
    }
}
    
/**
 * Initialize
 */
new PoP_Wassup_CDN_TemporaryFixHooks();
