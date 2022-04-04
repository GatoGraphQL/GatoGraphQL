<?php

class PoPTheme_Wassup_Core_ServiceWorkers_Hooks_Resources
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            $this->getPrecacheList(...),
            10,
            2
        );
    }

    public function getPrecacheList($precache, $resourceType)
    {
        if ($resourceType == 'static') {
            // Pre-caching only the .woff2 fonts, since if the browser has support for Service Workers, it will then also have support for woff2
            $precache[] = getBootstrapFontUrl();

            // // Add all the fonts needed by Bootstrap inside the bootstrap.min.css file
            // // Note: this file is in plug-in pop-coreprocessors, not in poptheme-wassup, however we
            // // add it here to not add a dependency from pop-coreprocessors to pop-serviceworkers
            // if (PoP_WebPlatform_ServerUtils::accessExternalcdnResources()) {

            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.eot';
            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.svg';
            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.ttf';
            //     // $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.woff';
            //     $precache[] = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/fonts/glyphicons-halflings-regular.woff2';
            // }
            // else {

            //     // $precache[] = POP_BOOTSTRAPWEBPLATFORM_URL.'/css/includes/fonts/glyphicons-halflings-regular.eot';
            //     // $precache[] = POP_BOOTSTRAPWEBPLATFORM_URL.'/css/includes/fonts/glyphicons-halflings-regular.svg';
            //     // $precache[] = POP_BOOTSTRAPWEBPLATFORM_URL.'/css/includes/fonts/glyphicons-halflings-regular.ttf';
            //     // $precache[] = POP_BOOTSTRAPWEBPLATFORM_URL.'/css/includes/fonts/glyphicons-halflings-regular.woff';
            //     $precache[] = POP_BOOTSTRAPWEBPLATFORM_URL.'/css/includes/fonts/glyphicons-halflings-regular.woff2';
            // }
        }

        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_Core_ServiceWorkers_Hooks_Resources();
