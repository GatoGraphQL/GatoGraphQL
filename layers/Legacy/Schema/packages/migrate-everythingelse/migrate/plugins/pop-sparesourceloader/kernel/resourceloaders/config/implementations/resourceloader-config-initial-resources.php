<?php

class PoP_SPAResourceLoader_FileReproduction_InitialResourcesConfig extends PoP_SPAResourceLoader_FileReproduction_AddResourcesConfigBase
{
    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_initialresources_configfile_renderer;
    //     return $pop_sparesourceloader_initialresources_configfile_renderer;
    // }

    protected function matchNature()
    {
        return 'generic';
    }

    protected function matchPaths()
    {
        // This shall provide an array with the following pages:
        // 1. getBackgroundloadRouteConfigurations:
        // POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES
        $routes = array_keys(PoP_SPA_ConfigurationUtils::getBackgroundloadRouteConfigurations());

        // Added through hooks:
        // 4. Logged-in User data page
        // Allow to hook in page POP_USERLOGIN_ROUTE_LOGGEDINUSERDATA
        $routes = array_filter(
            array_values(
                \PoP\Root\App::applyFilters(
                    'PoP_SPAResourceLoader_FileReproduction_InitialResourcesConfig:routes',
                    $routes
                )
            )
        );

        // Get the paths for all those routes
        $paths = array();
        foreach ($routes as $route) {
            $paths[] = $route.'/';
        }
        return $paths;
    }
}
