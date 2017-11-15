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

            // Add all the fonts needed by Bootstrap inside the bootstrap.min.css file
            // Note: this file is in plug-in pop-coreprocessors, not in poptheme-wassup, however we
            // add it here to not add a dependency from pop-coreprocessors to pop-serviceworkers
            if (PoP_Frontend_ServerUtils::access_externalcdn_resources()) {

                $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.eot';
                $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.svg';
                $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.ttf';
                $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.woff';
                $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.woff2';
            }
            else {

                $precache[] = POP_BOOTSTRAPPROCESSORS_URI.'/css/includes/fonts/glyphicons-halflings-regular.eot';
                $precache[] = POP_BOOTSTRAPPROCESSORS_URI.'/css/includes/fonts/glyphicons-halflings-regular.svg';

                // Comment Leo 16/11/2017: These files should be added to the Service Workers precache list, however if so we get JS error:
                // The FetchEvent for "https://assets.sdg-sse.org/wp-content/plugins/pop-bootstrapprocessors/css/includes/fonts/glyphicons-halflings-regular.woff" resulted in a network error response: an "opaque" response was used for a request whose type is not no-cors
                // And it just doesn't work! Even if adding mode: "no-cors" as recommended...
                // $precache[] = POP_BOOTSTRAPPROCESSORS_URI.'/css/includes/fonts/glyphicons-halflings-regular.ttf';
                // $precache[] = POP_BOOTSTRAPPROCESSORS_URI.'/css/includes/fonts/glyphicons-halflings-regular.woff';
                // $precache[] = POP_BOOTSTRAPPROCESSORS_URI.'/css/includes/fonts/glyphicons-halflings-regular.woff2';
            }
        }

        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Core_ServiceWorkers_Hooks_Resources();
