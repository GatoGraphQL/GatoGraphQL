<?php

class PoP_Frontend_Conversion_ResourceLoaderHooks {

    function __construct() {

        add_filter(
            'PoP_Frontend_ConversionManager:css-files',
            array($this, 'add_css_files')
        );
    }

    function add_css_files($files) {

        // Add all the CSS files from the Resource Loader
        global $pop_resourceloaderprocessor_manager;
        $resources = $pop_resourceloaderprocessor_manager->get_resources();
        $resources = $pop_resourceloaderprocessor_manager->filter_css($resources);
        foreach ($resources as $resource) {

            $files[] = $pop_resourceloaderprocessor_manager->get_asset_path($resource);
        }

    	return $files;
    }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Frontend_Conversion_ResourceLoaderHooks();