<?php

class PoP_ServiceWorkers_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_SW:configuration',
            array($this, 'getSwConfiguration')
        );
    }

    public function getSwConfiguration($configuration)
    {
        
        // Add the CDN configuration from pop-cdn
        // Fill default empty values, so that the corresponding $variables in sw.js get replaced
        if (POP_CDNFOUNDATION_CDN_CONTENT_URI) {
            $configuration['${contentCDNDomain}'] = POP_CDNFOUNDATION_CDN_CONTENT_URI;
            $configuration['${contentCDNParams}'] = array(
                GD_URLPARAM_CDNTHUMBPRINT,
            );
        }

        return $configuration;
    }
}
    
/**
 * Initialize
 */
new PoP_ServiceWorkers_CDN_Hooks();
