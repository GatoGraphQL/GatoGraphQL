<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_WebPlatform_CSSConverter_ResourceLoaderHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CSSConverter_ConversionManager:css-files',
            array($this, 'addCssFiles')
        );
    }

    public function addCssFiles($files)
    {
        // Add all the CSS files from the Resource Loader
        global $pop_resourceloaderprocessor_manager;
        $resources = $pop_resourceloaderprocessor_manager->getLoadedResources();
        $resources = $pop_resourceloaderprocessor_manager->filterCss($resources);
        foreach ($resources as $resource) {
            $files[] = $pop_resourceloaderprocessor_manager->getAssetPath($resource);
        }

        return $files;
    }
}


/**
 * Initialization
 */
new PoP_WebPlatform_CSSConverter_ResourceLoaderHooks();
