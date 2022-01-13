<?php

class PoPTheme_Wassup_ServiceWorkers_Hooks_Resources
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'getPrecacheList'),
            10,
            2
        );
    }

    public function getPrecacheList($precache, $resourceType)
    {
        if ($resourceType == 'static') {
            // Add all the images from this plug-in
            foreach (glob(POPTHEME_WASSUP_DIR."/img/*") as $file) {
                $precache[] = str_replace(POPTHEME_WASSUP_DIR, POPTHEME_WASSUP_URL, $file);
            }

            // Add all the images from the active theme
            $htmlcssplatformapi = \PoP\EngineHTMLCSSPlatform\FunctionAPIFactory::getInstance();
            $themeDir = $htmlcssplatformapi->getAssetsDirectory();
            $theme_uri = $htmlcssplatformapi->getAssetsDirectoryURI();
            foreach (glob($themeDir."/*") as $file) {
                $precache[] = str_replace($themeDir, $theme_uri, $file);
            }
        }
        
        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_ServiceWorkers_Hooks_Resources();
