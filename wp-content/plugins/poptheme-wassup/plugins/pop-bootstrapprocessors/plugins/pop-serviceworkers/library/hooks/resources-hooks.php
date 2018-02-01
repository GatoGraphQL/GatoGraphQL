<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_Core_ServiceWorkers_Hooks_Resources {

    function __construct() {
        
        add_filter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'get_precache_list'),
            10,
            2
        );
    }

    function get_precache_list($precache, $resourceType) {

        if ($resourceType == 'static') {

            // Pre-caching only the .woff2 fonts, since if the browser has support for Service Workers, it will then also have support for woff2
            $precache[] = get_bootstrap_font_url();

            // When generating the SW file, the $enqueuefile_type will be always "resource", then we must pre-cache the font twice: once for "resource", once for the chosen $enqueuefile_type
            if (!PoP_Frontend_ServerUtils::access_externalcdn_resources() && PoP_Frontend_ServerUtils::use_code_splitting() && PoP_Frontend_ServerUtils::loading_bundlefile(true)) {

                $precache[] = get_bootstrap_font_url('bundlefile');
            }

            // // Add all the fonts needed by Bootstrap inside the bootstrap.min.css file
            // // Note: this file is in plug-in pop-coreprocessors, not in poptheme-wassup, however we
            // // add it here to not add a dependency from pop-coreprocessors to pop-serviceworkers
            // if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.eot';
            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.svg';
            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.ttf';
            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.woff';
            //     $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.woff2';
            // }
            // else {

            //     // $precache[] = POP_BOOTSTRAPPROCESSORS_URL.'/css/includes/fonts/glyphicons-halflings-regular.eot';
            //     // $precache[] = POP_BOOTSTRAPPROCESSORS_URL.'/css/includes/fonts/glyphicons-halflings-regular.svg';
            //     // $precache[] = POP_BOOTSTRAPPROCESSORS_URL.'/css/includes/fonts/glyphicons-halflings-regular.ttf';
            //     // $precache[] = POP_BOOTSTRAPPROCESSORS_URL.'/css/includes/fonts/glyphicons-halflings-regular.woff';
            //     $precache[] = POP_BOOTSTRAPPROCESSORS_URL.'/css/includes/fonts/glyphicons-halflings-regular.woff2';
            // }
        }

        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Core_ServiceWorkers_Hooks_Resources();
