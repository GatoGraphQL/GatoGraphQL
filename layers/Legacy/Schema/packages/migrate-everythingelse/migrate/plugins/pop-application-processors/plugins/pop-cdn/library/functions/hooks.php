<?php
use PoPCMSSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;


class PoPThemeWassup_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            $this->getThumbprintPartialpaths(...),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        $routes = array();
        if ($thumbprint == POP_CDN_THUMBPRINT_POST) {
            $routes = array_filter(
                array(
                    POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS,
                    PostsComponentConfiguration::getPostsRoute(),
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
new PoPThemeWassup_CDN_Hooks();
