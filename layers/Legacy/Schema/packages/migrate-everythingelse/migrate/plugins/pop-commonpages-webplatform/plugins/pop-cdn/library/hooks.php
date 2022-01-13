<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoPSchema\Pages\Routing\PathUtils;
 
class PoP_CommonPagesWebPlatform_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'getThumbprintPartialpaths'),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        if ($thumbprint == POP_CDN_THUMBPRINT_USER) {
            $routes = array_filter(
                array(
                    POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE,
                )
            );
        } elseif ($thumbprint == POP_CDN_THUMBPRINT_PAGE) {
            $routes = array_filter(
                array(
                    POP_COMMONPAGES_ROUTE_ABOUT,
                )
            );
            $pages = array_filter(
                array(
                    POP_COMMONPAGES_PAGE_ABOUT_CONTENTGUIDELINES,
                    POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
                    POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
                )
            );
        }
        if ($routes) {
            foreach ($routes as $route) {
                $paths[] = $route.'/';
            }
        }
        if ($pages) {
            foreach ($pages as $page) {
                $paths[] = GeneralUtils::maybeAddTrailingSlash(PathUtils::getPagePath($page));
            }
        }
        
        return $paths;
    }
}

/**
 * Initialize
 */
new PoP_CommonPagesWebPlatform_CDN_Hooks();
